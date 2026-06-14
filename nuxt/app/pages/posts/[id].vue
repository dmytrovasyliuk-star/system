<template>
  <div class="m-8">
    <ClientOnly>
      <UCard v-if="post">
        <template #header>
          <h1 class="text-2xl font-bold">{{ post.title }}</h1>
        </template>

        <div class="p-4 text-gray-200" v-html="post.content_html || post.content_raw || 'Немає тексту'"></div>

        <template #footer>
          <UButton to="/BlogPostsUi" label="Назад до списку" variant="outline" color="gray" />
        </template>
      </UCard>

      <div v-else class="text-center p-8 text-gray-400">
        Завантаження посту...
      </div>
    </ClientOnly>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';

// Дістаємо ID з URL (наприклад, з /posts/1 дістанемо 1)
const route = useRoute();
const id = route.params.id;

const post = ref<any>(null);

const loadPost = async () => {
  try {
    // Звертаємося суто за конкретним ID, ніяких page!
    const response = await $fetch<any>(`http://127.0.0.1/api/blog/posts/${id}`);

    // Твій Laravel контролер загортає відповідь у data
    if (response && response.data) {
      post.value = response.data;
    }
  } catch (error) {
    console.error("Помилка завантаження посту:", error);
  }
};

// Запускаємо тільки в браузері, щоб уникнути багів з Docker
onMounted(() => {
  loadPost();
});
</script>
