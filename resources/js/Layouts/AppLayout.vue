<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/inertia-vue3';
import 'bootstrap'
import 'bootstrap/dist/css/bootstrap.css'
</script>

<template>
  <div class="container">
    <!-- Page Content -->
    <slot />
  </div>
</template>
