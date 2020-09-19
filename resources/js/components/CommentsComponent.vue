<template>
  <div class="container">

    <div class="half order-md-left text-md-left" style="width: 100%;">
        <p class="meta">
            <span>
                <i v-bind:class="[post_liked ? 'icon-heart' : 'icon-heart-o']" v-on:click="likePost()"> {{likes_count}}</i> 
            </span>
            <span><i class="icon-eye"> {{views_count}}</i></span>
        </p>
    </div>

    <div class="pt-2 mt-2" style="width: 100%;">
         <h3 class="mb-4 font-weight-bold">{{comments_count}} Comments</h3>
          <ul class="comment-list">
            <li class="comment" v-for="comment in comments" v-bind:key="comment.id">
              <div class="vcard bio">
                <img v-bind:src="comment.profile_photo" alt="Image placeholder">
              </div>
              <div class="comment-body">
                <div class="row">
                  <div class="col-6">
                    <h3>{{comment.author}}</h3>
                  </div>
                  <div class="col-6 text-right">
                    <button
                      v-if="comment.can_delete"
                      v-on:click="deleteComment(comment)"
                      class="btn btn-danger"
                    >Delete</button>
                  </div>
                </div>
                <div class="meta">{{comment.created_at}}</div>
                <p>{{comment.body}}</p>
              </div>
            </li>
          </ul>

          <button class="btn btn-success" style="width: 100%;" v-on:click="loadMoreComments()">
            <div class="spinner-border" role="status" v-if="this.loading"></div>
            <span v-else>Load more comments</span>
          </button>
          <hr />

          <div class="comment-form-wrap pt-2">
            <h3 class="mb-3">Leave a comment</h3>
            <form @submit.prevent class="p-3 bg-light">
              <div class="form-group">
                <label for="message">Message</label>
                <textarea v-model="message" id="message" cols="30" rows="5" class="form-control"></textarea>
              </div>
              <div class="form-group">
                <input
                  type="submit"
                  v-on:click="addComment()"
                  value="Post Comment"
                  class="btn py-3 px-4 btn-primary"
                />
              </div>
            </form>
          </div>

        <!-- END comment-list -->
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      comments: [],
      comments_count: 0,
      likes_count: 0,
      views_count: 0,
      post_liked: false,
      page: 2,
      message: "",
      loading: false,
    };
  },
  props: [
    'post_id'
  ],
  mounted() {
    console.log("Component mounted.");
    this.loadComments();

    Echo.channel(`comments.` + this.post_id).listen(
      "NewComment",
      (e) => {
        this.comments_count++;
        this.comments.unshift(e.comment);
        if(this.comments.length > 3){
          this.comments.pop();
        }
      }
    );

    Echo.channel(`comment.deleted.` + this.post_id).listen(
      "CommentDeleted",
      (e) => {

        this.comments = this.comments.filter((comment)=>comment.id !== e.comment.id );
        this.comments_count--;
      }
    );

    Echo.channel(`likes.new.` + this.post_id).listen(
      "NewLike",
      (e) => {
        this.likes_count++;
      }
    );

    Echo.channel(`likes.deleted.` + this.post_id).listen(
      "LikeDeleted",
      (e) => {
        this.likes_count--;
      }
    );

    Echo.channel(`views.` + this.post_id).listen(
      "NewView",
      (e) => {
        this.views_count++;
      }
    );
  },
  methods: {
    loadComments: function () {
      axios
        .get("/api/comments/" + this.post_id)
        .then((response) => {
          this.comments = response.data.comments;
          this.comments_count = response.data.comments_count;
          this.likes_count = response.data.likes_count;
          this.post_liked = response.data.post_liked;
          this.views_count = response.data.views_count;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    addComment: function () {
      axios
        .post("/api/comments/" + this.post_id, {
          message: this.message,
        })
        .then((response) => {
          this.comments.unshift(response.data);
          this.comments_count++;
        })
        .catch((error) => {
          if (error.response.status == 401) {
            alert("You must be logged in to post a comment");
          }
        });
    },
    loadMoreComments: function () {
      this.loading = true;

      axios
        .get("/api/comments/" + this.post_id + "?page=" + this.page)
        .then((response) => {
          if (response.data.comments.length !== 0) {
            response.data.comments.map((comment) => {
              this.comments.push(comment);
            });

            this.page++;
          }

          this.loading = false;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    deleteComment: function (comment) {
     if(window.confirm("Are you sure you want to delete this comment?")){
        axios
        .delete("/api/comments/" + comment.id)
        .then((response) => {
          alert(response.data.message);
          this.comments.splice(this.comments.indexOf(comment), 1);
          this.comments_count--;
        })
        .catch((error) => {
          if (error.response.status == 401) {
            alert("You must be logged in to delete a comment");
          } else if(error.response.status == 403){
            alert("Unauthorized");
          }
        });
     }
    },
    likePost: function() {
      axios
        .post("/api/like/" + this.post_id)
        .then((response) => {
          if (response.data.success === true) {
              if (response.data.liked === true) {
                  this.post_liked = !this.post_liked;
                  this.likes_count++;
              } else {
                  this.post_liked = !this.post_liked;
                  this.likes_count--;
              }
          }
        })
        .catch((error) => {
          if (error.response.status == 401) {
            alert("You must be logged in to like a post!!");
          }
        });
    }
  },
};
</script>
