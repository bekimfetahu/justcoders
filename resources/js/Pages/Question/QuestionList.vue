<script setup>
import ApplicationLayout from '@/Layouts/ApplicationLayout.vue';
import {Head, Link} from '@inertiajs/inertia-vue3'
</script>

<template>
  <div>

    <Head title="Questions" />
    <ApplicationLayout>
    <div class="container mx-auto bg-white p-3">
      <div class="grid grid-cols-8 gap-4 justify-center">
        <div class="border-r border-gray-200 mr-2">
        </div>
        <div class="col-span-4">
          <div class="flex justify-between">
            <div>
              <h3 class="text-xl font-bold mb-2">All Questions</h3>
            </div>
          </div>
          <div class="grid grid-cols-5 gap-4 justify-center mt-5">
            <div></div>
            <div class="col-span-4 offset-1">
              Filters
            </div>
          </div>
          <div v-for="question in questions.data">
            <div>
              <div class="grid grid-cols-5 gap-4 justify-center mt-5">
                <div>
                  <div class="text-xs text-gray-900 dark:text-white items-end">{{ question.votes }} votes</div>
                  <div class="text-xs text-gray-400 dark:text-white">{{ question.views }} views</div>
                  <div class="text-xs text-gray-400 dark:text-white">{{ question.answers }} answers</div>
                </div>
                <div class="col-span-4">
                  <div class="row-cols-12">
                    <Link :href="route('question.detail', [question.id, question.slug] )"
                       class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{ question.title }}</Link>
                  </div>
                  <div class="row-cols-12">
                    {{ question.content.substring(0, 200) }}...
                  </div>
                  <div class="row-cols-12">
                    <TagLink  v-for="tag in question.tags" class="mr-2" :href="route('questions.filer_tag',tag.name)">
                      {{ tag.name }}
                    </TagLink>
                  </div>
                  <div class="row-cols-12">
                    <div class="flex flex-row-reverse">
                      <div>
                        <p>asked {{ question.created_at_for_humans }}</p>
                        <Link :href="route('user.profile.view',question.asked_user.id) ">&nbsp; {{ question.asked_user.name }}</Link>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div>
              <hr class="h-px my-8 bg-gray-200 border-0 dark:bg-gray-700">
            </div>
          </div>
          <pagination :links="questions.links"></pagination>

        </div>
        <div class="border-gray-400 col-span-2">
          <div>
            <div><span class="text-xl mr-1">{{ question_count }}</span><span>questions</span></div>
            <div>
              <h3>Top Tags</h3>
              <div v-for="item in top_tags.data">
                <TagLink :href="route('questions.filer_tag',item.name)">
                  {{ item.name }}
                </TagLink> x <span class="text-xs">{{ item.total }}</span>
              </div>
              <h3 class="text-xl font-bold mb-2 mt-2">Newest questions</h3>
              <div v-for="quest in newest_questions.data">
                <Link :href="route('question.detail', [quest.id, quest.slug] )" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{quest.title}}</Link>
              </div>
            </div>
          </div>
        </div>
        <div class="border-brown-400">
          <Link :href="route('question.show_ask')"
                class="inline-flex items-center px-4 py-2 bg-blue-700 border border-transparent
                rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700
                focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500
                focus:ring-offset-2 transition ease-in-out duration-150">Ask Question
          </Link>
        </div>
      </div>
    </div>
  </ApplicationLayout>
  </div>
</template>

<script>

import FlashMessages from "@/Shared/FlashMessages.vue";
import HeaderLayout from "@/Layouts/HeaderLayout.vue";
import Pagination from "@/Components/Pagination.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TagLink from "@/Components/TagLink.vue";


export default {
  components: {
    PrimaryButton,
    Pagination,
    HeaderLayout,
    Head,
    Link,
    FlashMessages,
    TagLink
  },
  props: [
    'questions',
    'newest_questions',
    'question_count',
    'top_tags'
  ],
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        title: '',
        content: '',
        tag: '',
      }),
    }
  },
  mounted() {
    console.log()
  },
  methods: {
    click() {
      alert('click')
    },
    clearForm() {
      this.form.title = ''
      this.form.content = ''
      this.form.tag = ''

    }
  },
}
</script>

<style>
/*!
 * Start Bootstrap - Modern Business (http://startbootstrap.com/)
 * Copyright 2013-2016 Start Bootstrap
 * Licensed under MIT (https://github.com/BlackrockDigital/startbootstrap/blob/gh-pages/LICENSE)
 */

/* Global Styles */

html,
body {
  height: 100%;
}

body {
  padding-top: 50px; /* Required padding for .navbar-fixed-top. Remove if using .navbar-static-top. Change if height of navigation changes. */
}

.img-portfolio {
  margin-bottom: 30px;
}

.img-hover:hover {
  opacity: 0.8;
}

/* Home Page Carousel */

header.carousel {
  height: 50%;
}

header.carousel .item,
header.carousel .item.active,
header.carousel .carousel-inner {
  height: 100%;
}

header.carousel .fill {
  width: 100%;
  height: 100%;
  background-position: center;
  background-size: cover;
}

/* 404 Page Styles */

.error-404 {
  font-size: 100px;
}

/* Pricing Page Styles */

.price {
  display: block;
  font-size: 50px;
  line-height: 50px;
}

.price sup {
  top: -20px;
  left: 2px;
  font-size: 20px;
}

.period {
  display: block;
  font-style: italic;
}

/* Footer Styles */

footer {
  margin: 50px 0;
}

/* Responsive Styles */

@media (max-width: 991px) {
  .customer-img,
  .img-related {
    margin-bottom: 30px;
  }
}

@media (max-width: 767px) {
  .img-portfolio {
    margin-bottom: 15px;
  }

  header.carousel .carousel {
    height: 70%;
  }
}

.login-box {
  width: 280px;
  margin: 0 auto;
  padding: 30px;
  border: 1px solid #e4e6e8;
  background: #FFF;
}

.page-header {
  padding-bottom: 10px;
  margin: 44px 0 22px;
}

.text-black {
  color: #242729;
}

.text-grey {
  color: #9199a1;
}

.text-brown {
  color: #848d95;
}

.add-a-comment {
  color: #848d95;
  opacity: 0.6;
}

.question-title {
  border-bottom: 1px solid #e4e6e8;
}

.question-author-info {
  background-color: #E0EAF1;
  padding: 10px;
}

.question-author-info img, .answer-author-info img {
  float: left;
}

.tag-list .item {
  background-color: #E1ECF4;
  color: #39739d;
  margin: 5px 5px 5px 0;
  padding: 5px;
}

.question-list .title {
  color: #07C;
  font-size: 16px;
  margin-bottom: 1.2em;
}

.question-list .excerpt {
  color: #3b4045;
  padding-bottom: 5px;
}

.question-list .question-item {
  padding: 12px 0 10px 0;
  border-bottom: 1px solid #e4e6e8;
}

.question-item .votes .count, .question-item .status .count {
  color: #6a737c;
  font-size: 20px;
}

.question-item .votes p, .question-item .status p {
  color: #6a737c;
  font-size: 11px;
}

.ask-question {
  margin-top: 44px;
}

.ask-question .questions-count {
  font-size: 26px;
}

.accepted {
  color: #5eba7d;
}

.voted {
  color: #f48024;
}
</style>
