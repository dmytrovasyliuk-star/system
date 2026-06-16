<template>
  <div class="m-8 max-w-3xl mx-auto">
    <div class="mb-4">
      <UButton to="/BlogPostsUi" icon="i-heroicons-arrow-left" color="gray" variant="link">
        Назад до списку
      </UButton>
    </div>

    <div v-if="pending" class="text-center py-12 text-gray-500">
      Завантаження даних...
    </div>

    <div v-else-if="error" class="text-center py-12 text-red-500">
      Помилка сервера: {{ error.message }}
    </div>

    <UCard v-else-if="post">
      <template #header>
        <div class="flex justify-between items-center">
          <h1 class="text-2xl font-bold text-gray-100">{{ post.title }}</h1>
          <span :class="post.is_published ? 'bg-green-900/50 text-green-400' : 'bg-red-900/50 text-red-400'" class="px-2 py-1 rounded text-xs font-semibold">
            {{ post.is_published ? 'Опубліковано' : 'Чернетка' }}
          </span>
        </div>
      </template>

      <div class="space-y-4">
        <div>
          <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">ID поста</h3>
          <p class="text-gray-300 text-sm"># {{ post.id }}</p>
        </div>

        <div>
          <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Slug</h3>
          <p class="text-blue-400 text-sm font-mono">{{ post.slug }}</p>
        </div>

        <div>
          <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Дата публікації</h3>
          <p class="text-gray-300 text-sm">{{ post.date_published || 'Не опубліковано' }}</p>
        </div>
      </div>
    </UCard>
  </div>
</template>

<script setup lang="ts">
const route = useRoute();

// 1. Описуємо структуру нашого поста (згідно з тим, що віддає Laravel)
interface Post {
  id: number;
  title: string;
  slug: string;
  is_published: boolean;
  date_published: string;
}

// 2. Описуємо структуру обгортки API
interface ApiResponse {
  data: Post;
}

// 3. Вказуємо цей тип для useFetch
const { data: response, pending, error } = await useFetch<ApiResponse>(
  `http://localhost/api/admin/blog/posts/${route.params.id}`,
  {
    server: false,
    key: `post-${route.params.id}`
  }
);

// 4. Тепер TS знає, що 'response.value' має властивість 'data'
const post = computed(() => response.value?.data);
</script>
