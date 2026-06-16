<template>
  <div class="m-8">
    <UCard>
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-800">
          <thead>
          <tr>
            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-400">#</th>
            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-400">Заголовок</th>
            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-400">Дата публікації</th>
            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-400">Статус</th>
            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-400">Дії</th>
          </tr>
          </thead>
          <tbody class="divide-y divide-gray-800">
          <tr v-for="post in posts" :key="post.id" class="hover:bg-gray-900/50">
            <td class="px-4 py-3 text-sm text-gray-300">{{ post.id }}</td>

            <td class="px-4 py-3 text-sm text-gray-200">
              <NuxtLink :to="`/posts/${post.id}`" class="hover:text-green-400 hover:underline transition font-medium">
                {{ post.title }}
              </NuxtLink>
            </td>

            <td class="px-4 py-3 text-sm text-blue-400">{{ post.date_published || '—' }}</td>

            <td class="px-4 py-3 text-sm">
              <span :class="post.is_published ? 'text-green-400' : 'text-red-400'">
                {{ post.is_published ? 'Опубліковано' : 'Чернетка' }}
              </span>
            </td>

            <td class="px-4 py-3 text-sm text-center">
              <UButton
                :to="`/posts/${post.id}`"
                size="xs"
                color="green"
                variant="ghost"
                icon="i-heroicons-eye"
              >
                Читати
              </UButton>
            </td>
          </tr>
          </tbody>
        </table>

        <div v-if="pending" class="text-center py-8 text-gray-500">
          Завантаження даних...
        </div>
        <div v-else-if="posts.length === 0" class="text-center py-8 text-gray-500">
          Таблиця порожня.
        </div>
      </div>

      <div class="flex justify-between items-center p-4 border-t border-gray-800 mt-4">
        <button
          @click="page--"
          :disabled="page <= 1 || pending"
          class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded disabled:opacity-30 disabled:cursor-not-allowed transition"
        >
          &laquo; Попередня
        </button>

        <span class="text-gray-400 font-medium">Сторінка {{ page }} з {{ lastPage }}</span>

        <button
          @click="page++"
          :disabled="page >= lastPage || pending"
          class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded disabled:opacity-30 disabled:cursor-not-allowed transition"
        >
          Наступна &raquo;
        </button>
      </div>
    </UCard>
  </div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue';

const page = ref(1);

// Використовуємо key, щоб при зміні page запит примусово перезапускався
const { data: response, pending, refresh } = await useFetch<any>(
  () => `http://127.0.0.1/api/admin/blog/posts?page=${page.value}`,
  {
    server: false,
    key: 'posts-page-' + page.value // Це змушує Nuxt думати, що це новий запит
  }
);

// Слідкуємо за зміною page і оновлюємо дані
watch(page, () => {
  refresh();
});

const posts = computed(() => response.value?.data || []);
const lastPage = computed(() => response.value?.meta?.last_page || 1);
</script>
