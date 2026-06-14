<template>
  <div class="p-4">
    <table class="w-full border-collapse border">
      <thead>
      <tr class="bg-gray-200">
        <th>#</th><th>Автор</th><th>Заголовок</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="post in posts" :key="post.id" class="border-b">
        <td class="p-2">{{ post.id }}</td>
        <td class="p-2">{{ post.user?.name }}</td>
        <td class="p-2">{{ post.title }}</td>
      </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup lang="ts">
const posts = ref([]);
const { data } = await useFetch('http://localhost:8000/api/blog/posts');
posts.value = data.value?.data || [];
</script>
