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

    <div class="comment-form-wrap pt-2">
      <h3 class="mb-3">Leave a comment</h3>
      <form @submit.prevent class="p-3 p-md-5 bg-light">
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
      message: "",
    };
  },
  mounted() {
    console.log("Component mounted.");
    this.loadComments();
  },
  methods: {
    loadComments: function () {
      axios.get("/api/comments/" + this.$attrs.post_id).then((response) => {
        this.comments = response.data;
        this.comments_count = this.comments.length;
      });
    },
    addComment: function () {
      axios
        .post("/api/comments/" + this.$attrs.post_id, {
          message: this.message,
        })
        .then((response) => {
          this.comments.push(response.data);
          this.comments_count++;
        });
    },
  },
};
</script>
