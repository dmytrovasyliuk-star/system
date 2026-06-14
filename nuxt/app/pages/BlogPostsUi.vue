<template>
  <div class="m-8">
    <UCard>
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-800">
          <thead>
          <tr>
            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-400">#</th>
            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-400">Заголовок</th>
          </tr>
          </thead>
          <tbody class="divide-y divide-gray-800">
          <tr v-for="post in posts" :key="post.id" class="hover:bg-gray-900/50">
            <td class="px-4 py-3 text-sm text-gray-300">{{ post.id }}</td>
            <td class="px-4 py-3 text-sm text-gray-200">{{ post.title }}</td>
          </tr>
          </tbody>
        </table>

        <div v-if="posts.length === 0" class="text-center py-8 text-gray-500">
          Завантаження даних або таблиця порожня...
        </div>
      </div>

      <div class="flex justify-between items-center p-4 border-t border-gray-800 mt-4">
        <button
          @click="page--"
          :disabled="page <= 1"
          class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded disabled:opacity-30 disabled:cursor-not-allowed transition"
        >
          &laquo; Попередня
        </button>

        <span class="text-gray-400 font-medium">Сторінка {{ page }} з {{ lastPage }}</span>

        <button
          @click="page++"
          :disabled="page >= lastPage"
          class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded disabled:opacity-30 disabled:cursor-not-allowed transition"
        >
          Наступна &raquo;
        </button>
      </div>
    </UCard>
  </div>
</template>

<script setup lang="ts">
import { ref, watch, onMounted } from 'vue';

const page = ref(1);
const lastPage = ref(1);
const posts = ref<any[]>([]);

const loadData = async () => {
  try {
    const response = await $fetch<any>(`http://127.0.0.1/api/blog/posts?page=${page.value}`);
    if (response && response.data) {
      posts.value = response.data;
      // Дістаємо з Laravel інформацію про те, яка сторінка остання
      lastPage.value = response.last_page || 1;
    }
  } catch (error) {
    console.error("Помилка:", error);
  }
};

// Коли змінюється page, автоматично вантажимо нові дані
watch(page, loadData);

onMounted(() => {
  loadData();
});
</script>
