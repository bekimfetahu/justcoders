<template>
  <div>
    <HeaderLayout></HeaderLayout>
    <div class="container mx-auto">
      <div class="grid grid-cols-8">
        <div class="col-start-2 col-span-6">
          <div class="mb-6">
            <label for="title" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">What is your
              question?</label>
            <input type="text" id="title" v-model="form.title"
                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
          </div>
          <div class="mb-6">
            <label for="message" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
              Describe your question
            </label>
            <textarea id="message" rows="4" v-model="form.content"
                      class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            ></textarea>
          </div>
          <div class="mb-6">
            <label for="message" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tags</label>
            <input v-model="form.tags" type="text" placeholder="Tag" name="tag"  data-role="tagsinput" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
          </div>
          <button type="button" @click="submitQuestion"
                  class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            Submit
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

import {Head, Link} from '@inertiajs/inertia-vue3'
import FlashMessages from "@/Shared/FlashMessages.vue";
import HeaderLayout from "@/Layouts/HeaderLayout.vue";


export default {
  components: {
    Head,
    Link,
    FlashMessages,
    HeaderLayout,
  },
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
  methods: {
    submitQuestion() {
      let vm = this
      this.form.post(`/questions/ask-question`, {
        onSuccess: (res) => {
          console.log(res)
          vm.clearForm()
        },
      })
    },
    clearForm() {
      this.form.title = ''
      this.form.content = ''
      this.form.tag = ''

    }
  },
}
</script>