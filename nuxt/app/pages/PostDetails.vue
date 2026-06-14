<template>
  <UCard v-if="post" class="m-8">
    <template #header>
      <h1 class="text-2xl font-bold">{{ post.title }}</h1>
    </template>

    <div class="p-4">
      <p class="text-gray-600 mb-4">Автор: {{ post.user?.name }}</p>
      <div class="prose">{{ post.body }}</div>
    </div>
  </UCard>
  <div v-else class="p-8 text-center">Завантаження...</div>
</template>

<script setup lang="ts">
const route = useRoute();
const id = route.params.id;

const { data } = await useFetch(`http://localhost:8000/api/blog/posts/${id}`) as any;
const post = computed(() => data.value?.data || null);
</script>
