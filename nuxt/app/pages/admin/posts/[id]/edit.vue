<script setup lang="ts">
import { z } from 'zod'
import type { FormSubmitEvent } from '#ui/types'

const route = useRoute()
const router = useRouter()
const toast = useToast()
const postId = route.params.id

// 1. Завантажуємо дані цієї конкретної статті (вимкнено SSR)
const { data: responseData, pending: postPending } = await useFetch<any>(`http://localhost/api/admin/blog/posts/${postId}`, {
  mode: 'cors',
  server: false
})

// 2. Завантажуємо список категорій для випадаючого списку
const { data: categoriesResponse } = await useFetch<any>('http://localhost/api/admin/blog/categories', {
  mode: 'cors',
  server: false
})

const categoriesList = computed(() => {
  const rawArray = categoriesResponse.value?.data || []
  return rawArray.map((item: any) => ({
    label: item.title,
    value: item.id
  }))
})

// Схема валідації суворо під конвенцію бекенду
const schema = z.object({
  title: z.string().min(5, 'Заголовок має містити мінімум 5 symbols'),
  slug: z.string().min(3, 'Slug обов\'язковий'),
  content_raw: z.string().min(10, 'Текст статті має бути довшим за 10 символів'),
  category_id: z.number({ message: 'Оберіть категорію' })
})

type Schema = z.output<typeof schema>

const state = reactive({
  title: '',
  slug: '',
  content_raw: '',
  category_id: undefined as number | undefined
})

// Заповнюємо форму даними з бази, щойно вони завантажаться в браузер
watch(() => responseData.value, (newVal) => {
  if (newVal) {
    const post = newVal.data || newVal
    state.title = post.title || ''
    state.slug = post.slug || ''
    state.content_raw = post.content_raw || ''
    state.category_id = post.category_id
  }
}, { immediate: true })

// Функція транслітерації українських літер для безпечного URL рядка
const transliterate = (text: string) => {
  const ukrainianToLatin: Record<string, string> = {
    'а': 'a', 'б': 'b', 'в': 'v', 'г': 'h', 'ґ': 'g', 'д': 'd', 'е': 'e', 'є': 'ye',
    'ж': 'zh', 'з': 'z', 'и': 'y', 'і': 'i', 'ї': 'yi', 'й': 'y', 'к': 'k', 'л': 'l',
    'м': 'm', 'н': 'n', 'о': 'o', 'п': 'p', 'р': 'r', 'с': 's', 'т': 't', 'у': 'u',
    'ф': 'f', 'х': 'kh', 'ц': 'ts', 'ч': 'ch', 'ш': 'sh', 'щ': 'shch', 'ь': '', 'ю': 'yu', 'я': 'ya'
  }

  return text
    .toLowerCase()
    .split('')
    .map(char => ukrainianToLatin[char] !== undefined ? ukrainianToLatin[char] : char)
    .join('')
    .trim()
    .replace(/[\s_-]+/g, '-')
    .replace(/[^\w-]/g, '')
    .replace(/^-+|-+$/g, '')
}

// Автоматично оновлюємо slug, тільки якщо користувач руками змінює заголовок статті
watch(() => state.title, (newTitle) => {
  const originalTitle = responseData.value?.data?.title || responseData.value?.title || ''
  if (originalTitle && originalTitle !== newTitle) {
    state.slug = transliterate(newTitle)
  }
})

async function onSubmit(event: FormSubmitEvent<Schema>) {
  try {
    await $fetch(`http://localhost/api/admin/blog/posts/${postId}`, {
      method: 'PUT',
      body: event.data,
      mode: 'cors'
    })

    toast.add({ title: 'Успішно', description: 'Статтю оновлено', color: 'success' })
    router.push('/admin/posts')
  } catch (error: any) {
    console.error(error)

    const backendMessage = error.data?.message || 'Не вдалося оновити статтю.'
    const validationErrors = error.data?.errors
      ? Object.values(error.data.errors).flat().join(', ')
      : ''

    toast.add({
      title: 'Помилка валідації бекенду',
      description: validationErrors || backendMessage,
      color: 'error'
    })
  }
}
</script>

<template>
  <div class="p-8 max-w-2xl mx-auto">
    <div class="mb-6 flex items-center gap-4">
      <UButton to="/admin/posts" color="neutral" variant="ghost" icon="i-heroicons-arrow-left" />
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Редагування статті</h1>
    </div>

    <div v-if="postPending" class="flex justify-center p-12">
      <UIcon name="i-heroicons-arrow-path" class="w-8 h-8 animate-spin text-gray-500" />
    </div>

    <UCard v-else>
      <UForm :schema="schema" :state="state" class="space-y-6" @submit="onSubmit">

        <UFormField label="Заголовок статті" name="title" required>
          <UInput v-model="state.title" class="w-full" />
        </UFormField>

        <UFormField label="Slug (URL)" name="slug" required>
          <UInput v-model="state.slug" class="w-full" />
        </UFormField>

        <UFormField label="Категорія" name="category_id" required>
          <USelect
            v-model.number="state.category_id"
            :items="categoriesList"
            placeholder="Оберіть категорію зі списку"
            class="w-full"
          />
        </UFormField>

        <UFormField label="Текст статті" name="content_raw" required>
          <UTextarea v-model="state.content_raw" :rows="8" class="w-full" />
        </UFormField>

        <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-800">
          <UButton to="/admin/posts" color="neutral" variant="soft">Скасувати</UButton>
          <UButton type="submit" color="primary">Оновити статтю</UButton>
        </div>
      </UForm>
    </UCard>
  </div>
</template>
