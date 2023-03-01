<script setup>
import ApplicationLayout from '@/Layouts/ApplicationLayout.vue';
import {Head, Link} from '@inertiajs/inertia-vue3'
</script>
<template>
  <div>
    <Head title="Question Details"/>
    <ApplicationLayout>
      <div class="container mx-auto bg-white p-3">
        <div class="grid grid-cols-8" v-if="questionData">
          <div class="col-span-1 border-r border-gray-200 mr-2"></div>
          <div class="col-span-4">
            <div class="text-xl font-bold">{{ question.title }} BEKI</div>
            <div class="grid grid-cols-6">
              <div>
                <div class="mb-2">
                  <a href="#" @click="voteQuestion(question.id,1)">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="w-7 h-7">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5"/>
                    </svg>
                  </a>
                </div>
                <div class="p-2">{{ questionData.votes }}</div>
                <div class="mb-2">
                  <a href="#" @click="voteQuestion(question.id,0)" class="mt-3">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="w-7 h-7">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                    </svg>
                  </a>
                </div>
              </div>
              <div class="col-span-4">
                <div>{{ question.content }}</div>
                <div>
                  <TagLink :href="route('questions.filer_tag',tag.name)" v-for="tag in questionData.tags" class="mr-2">
                    {{ tag.name }}
                  </TagLink>
                </div>
                <div>
                  <Link :href="route('question.show_edit', question.id)"
                        v-show="userId == question.asked_user_id">edit
                  </Link>
                  <!--                <a href="" class="text-brown" v-show="userId == question.asked_user_id">delete</a>-->
                </div>
                <div>
                  <p class="font-thin">asked {{ question.created_at_for_humans }}</p>
                  <!--                <img width="60px" height="60px" v-bind:src="question.asked_user_avatar"/>-->
                  <!--                <a :href="'/user/' + question.asked_user_id + '/view'">&nbsp; {{ question.asked_user.name }}</a>-->
                </div>
              </div>
              <div class="col-span-2"></div>
            </div>
          </div>
          <div>
          </div>
        </div>
        <div class="grid grid-cols-8">
          <div class="col-span-1 border-r border-gray-200 mr-2"></div>
          <div class="col-span-4">
            <div class="text-xl font-bold">{{ answers.length }} answers</div>
            <template v-for="answer in answers">
              <div class="grid grid-cols-5">
                <div>
                  <div class="mb-2">
                    <a href="#" @click="voteAnswer(answer.id,1)">
                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                           stroke="currentColor" class="w-7 h-7">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5"/>
                      </svg>
                    </a>
                  </div>
                  <div class="p-2">{{ answer.votes }}</div>
                  <div class="mb-2">
                    <a href="#" @click="voteAnswer(question.id,0)" class="mt-3">
                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                           stroke="currentColor" class="w-7 h-7">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                      </svg>
                    </a>
                  </div>
                </div>
                <div class="col-span-4">
                  <div class="text-black">{{ answer.content }}</div>
                  <div class="mb-2"><hr /></div>
                  <div>
                    <Link :href="route('question.show_edit', question.id)"
                          v-show="userId == answer.user_id">edit
                    </Link>
                    <!--                <a href="" class="text-brown" v-show="userId == question.asked_user_id">delete</a>-->
                  </div>
                  <div v-for="comment in answer.children">
                    <div class="col-lg-offset-2 col-lg-10">
                      <div class="text-sm">{{ comment.content }} -
                        <Link :href="route('user.profile.view',comment.user.id)" class="text-blue-800">
                          {{ comment.user.name }}
                        </Link>
                        <span class="text-gray-900/75 text-xs">  at {{ comment.created_at_formatted }}</span>
                        <!--                    - <a :href="'/user/' + comment.user.id  + '/view'" class="commented-by" >&nbsp; {{ comment.user.name }}</a>-->
                        <!--                    <span class="commented-at">{{ comment.created_at }}</span>-->
                      </div>
                    </div>
                    <div class="mb-2"><hr /></div>
                  </div>
                  <div class="flex flex-row-reverse">
                    <div><p>asked {{ answer.created_at_for_humans }}</p>
                      <!--                <img width="60px" height="60px" v-bind:src="question.asked_user_avatar"/>-->
                      <Link :href="route('user.profile.view',answer.user.id) ">&nbsp; {{ answer.user.name }}</Link>
                    </div>

                  </div>
                  <a href="#"
                     class="text-blue-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300 hover:underline"
                     @click="answer.show_comment = !answer.show_comment">
                    {{ answer.show_comment ? 'Close' : 'Comment answer' }}
                  </a>
                  <div v-if="answer.show_comment">
                    <textarea v-model="answer.new_comment" style="width:100%"></textarea>
                    <PrimaryButton
                        @click="doneComment(answer, route('question.answer.comment', [question.id, answer.id]))">Add
                      Comment
                    </PrimaryButton>
                  </div>

                </div>
                <div class="col-span-2"></div>
              </div>
              <div class="grid grid-cols-7">
                <div class="col-span-7 p-4"><hr /></div>
              </div>
            </template>
            <div>
              <p>New Comment</p>
              <br/>
              <textarea v-model="newComment" style="width:100%"></textarea>
              <PrimaryButton @click="addComment">Add Comment</PrimaryButton>
            </div>
          </div>
          <div>
          </div>
        </div>
      </div>
    </ApplicationLayout>
  </div>
</template>

<script>

import HeaderLayout from "@/Layouts/HeaderLayout.vue";
import axios from "axios";
import TagLink from "@/Components/TagLink.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

export default {
  components: {
    PrimaryButton,
    TagLink,
    HeaderLayout,
  },
  data() {
    return {
      questionData: null,
      answers: [],
      isActive: 'red',
      constants: [],
      isAuthenticated: false,
      userId: 0,
      newComment: null,
      answerComment: ''
    };
  },
  mounted() {

    this.fetchQuestionData();
    this.fetchAnswers();
    this.fetchConstants();
    this.checkAuthenticated();
  },
  methods: {

    fetchConstants() {
      axios.get('/api/get_constant/').then((res) => {
        this.constants = res.data;
      });
    },
    checkAuthenticated() {
      axios.get('/user/isAuthenticated/').then((res) => {
        this.isAuthenticated = res.data;
        if (this.isAuthenticated) {
          this.getCurrentUserId();
        }

      });
    },
    getCurrentUserId() {
      axios.get('/user/getCurrentUserId/').then((res) => {
        this.userId = res.data;
      });
    },
    fetchQuestionData() {
      axios.get('/question/' + this.question.id).then((res) => {
        this.questionData = res.data;

      })
    },
    fetchAnswers() {

      axios.get('/answers/' + this.question.id)
          .then((res) => {
            res.data.map(function (value, key) {
              value.showAddComment = false;
            });
            this.answers = res.data;
          });
    },
    resetUpVoteDownVote() {
      this.question.down_voted = false;
      this.question.up_voted = false;
    },
    showComment: function (answer) {
      answer.showAddComment = true;
    },
    doneComment: function (answer, route) {
      axios.post(route, {
        content: answer.new_comment
      }).then((res) => {
        if (res.data.status) {
          this.fetchAnswers();
        }
      })
    },
    addComment: function () {

      axios.post('/questions/' + this.questionData.id + '/comment', {
        content: this.newComment
      })
          .then((res) => {
            if (res.data.status) {
              this.fetchAnswers();
            }
          })

    },
    voteQuestion: function (vote_id, vote_type) {


      if (this.$page.props.auth.user.id === this.question.created_by) {
        alert("Can't vote your own post");
        return;
      }

      axios.post('/vote_action', {
        vote_id: vote_id,
        vote_type: vote_type,
        vote_category: 0
      })
          .then((res) => {
            if (res.data.status) {
              this.questionData.votes = res.data.votes;
              this.questionData.up_voted = res.data.up_voted;
              this.questionData.down_voted = res.data.down_voted;
            }
          })
          .catch((err) => {
            if (err.response.status == 401) {
              alert("You must login before using this function");
            }
          });
    },
    acceptAnswer: function (answer_id, index) {
      axios.post('/accept_answer', {
        answer_id: answer_id,
        question_id: this.question.id
      })
          .then((res) => {
            if (res.data.status) {

              this.answers.map(function (value, key) {
                value.accepted = false;
              });
              this.answers[index].accepted = true;
            }
          })
          .catch((err) => {
            if (err.response.status == 401) {
              alert("You must login before using this function");
            }
          });
    },
    voteAnswer: function (vote_id, vote_type, index) {

      console.log(this.userId)
      console.log(this.answers[index].user.id)
      if (this.userId == this.answers[index].user.id) {
        alert("Can't vote your own post");
        return;
      }
      axios.post('/vote_action', {
        vote_id: vote_id,
        vote_type: vote_type,
        vote_category: this.constants.vote_category.ANSWER
      })
          .then((res) => {
            if (res.data.status) {
              this.answers[index].votes = res.data.votes;
              this.answers[index].up_voted = res.data.up_voted;
              this.answers[index].down_voted = res.data.down_voted;
            }
          })
          .catch((err) => {
            if (err.response.status == 401) {
              alert("You must login before using this function");
            }
          });
    }
  },
  props: [
    'question',
    'newest_questions',
    'currentUserId',
    'isLogin'
  ]
}
</script>
