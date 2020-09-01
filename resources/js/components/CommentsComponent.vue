<template>
  <div class="container">
    <h3 class="mb-4 font-weight-bold">{{comments_count}} Comments</h3>
    <ul class="comment-list">
      <li class="comment" v-for="comment in comments" v-bind:key="comment.id">
        <div class="vcard bio"></div>
        <div class="comment-body">
          <h3>{{comment.author}}</h3>
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
  </div>
</template>

<script>
export default {
  data() {
    return {
      comments: [],
      comments_count: 0,
      page: 1,
      message: "",
      loading: false,
    };
  },
  mounted() {
    console.log("Component mounted.");
    this.loadComments();
  },
  methods: {
    loadComments: function () {
      axios
        .get("/api/comments/" + this.$attrs.post_id)
        .then((response) => {
          this.comments = response.data.comments;
          this.comments_count = response.data.comments_count;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    addComment: function () {
      axios
        .post("/api/comments/" + this.$attrs.post_id, {
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
      this.page++;
      this.loading = true;
      axios
        .get("/api/comments/" + this.$attrs.post_id + "?page=" + this.page)
        .then((response) => {
          response.data.comments.map((comment) => {
            this.comments.push(comment);
          });

          this.loading = false;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
  },
};
</script>
