<template>
  <UCard class="m-8">
    <UTable :rows="posts" :columns="columns" />
    <div class="flex justify-center p-4">
      <UPagination v-model="page" :page-count="10" :total="total" />
    </div>
  </UCard>
</template>

<script setup lang="ts">
const page = ref(1);
const total = ref(0);
const posts = ref([]);

// Додано унікальні ID до кожного об'єкта columns для виправлення помилки 500
const columns = [
  { key: 'id', label: '#', id: 'col_id' },
  { key: 'user.name', label: 'Автор', id: 'col_author' },
  { key: 'category.title', label: 'Категорія', id: 'col_category' },
  { key: 'title', label: 'Заголовок', id: 'col_title' }
];

const fetchPosts = async () => {
  try {
    // Додано 'as any' для уникнення помилок TypeScript
    const response = await $fetch(`http://localhost:8000/api/blog/posts?page=${page.value}`) as any;

    // Переконуємось, що ми беремо дані з правильного місця
    posts.value = response.data || [];
    total.value = response.meta?.total || 0;
  } catch (error) {
    console.error("Помилка завантаження API:", error);
  }
};

watch(page, fetchPosts);
fetchPosts();
</script>
