<script setup lang="ts">
import { z } from 'zod'
import type { FormSubmitEvent } from '#ui/types'

const route = useRoute()
const router = useRouter()
const toast = useToast()
const categoryId = route.params.id

// 1. Завантажуємо дані САМЕ цієї категорії (з вимкненим SSR)
const { data: responseData, pending: categoryPending } = await useFetch<any>(`http://localhost/api/admin/blog/categories/${categoryId}`, {
  mode: 'cors',
  server: false
})

// 2. Завантажуємо список усіх категорій для випадаючого списку (щоб змінити батьківську)
const { data: categoriesResponse } = await useFetch<any>('http://localhost/api/admin/blog/categories', {
  mode: 'cors',
  server: false
})

// Трансформуємо список під правила Nuxt UI v3: { label, value }
const categoriesList = computed(() => {
  const rawArray = categoriesResponse.value?.data || []
  return rawArray.map((item: any) => ({
    label: item.title,
    value: item.id
  }))
})

const schema = z.object({
  title: z.string().min(5, 'Назва має містити мінімум 5 символів'),
  slug: z.string().min(3, 'Slug обов\'язковий (мінімум 3 символи)'),
  parent_id: z.number({ message: 'Оберіть батьківську категорію' })
})

type Schema = z.output<typeof schema>

const state = reactive({
  title: '',
  slug: '',
  parent_id: undefined as number | undefined
})

// Наповнюємо форму даними, щойно вони прилетять із бекенду в браузер
watch(() => responseData.value, (newVal) => {
  if (newVal) {
    const category = newVal.data || newVal
    state.title = category.title || ''
    state.slug = category.slug || ''
    state.parent_id = category.parent_id
  }
}, { immediate: true })

async function onSubmit(event: FormSubmitEvent<Schema>) {
  try {
    await $fetch(`http://localhost/api/admin/blog/categories/${categoryId}`, {
      method: 'PUT',
      body: event.data,
      mode: 'cors'
    })

    toast.add({ title: 'Успішно', description: 'Категорію оновлено', color: 'success' })
    router.push('/admin/categories')
  } catch (error: any) {
    console.error(error)

    const backendMessage = error.data?.message || 'Не вдалося оновити категорію.'
    const validationErrors = error.data?.errors
      ? Object.values(error.data.errors).flat().join(', ')
      : ''

    toast.add({
      title: 'Помилка валідації бекенду (422)',
      description: validationErrors || backendMessage,
      color: 'error'
    })
  }
}
</script>

<template>
  <div class="p-8 max-w-2xl mx-auto">
    <div class="mb-6 flex items-center gap-4">
      <UButton to="/admin/categories" color="neutral" variant="ghost" icon="i-heroicons-arrow-left" />
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Редагування категорії</h1>
    </div>

    <div v-if="categoryPending" class="flex justify-center p-6">
      <UIcon name="i-heroicons-arrow-path" class="w-8 h-8 animate-spin text-gray-500" />
    </div>

    <UCard v-else>
      <UForm :schema="schema" :state="state" class="space-y-6" @submit="onSubmit">

        <UFormField label="Назва категорії" name="title" required>
          <UInput v-model="state.title" class="w-full" />
        </UFormField>

        <UFormField label="Slug (URL)" name="slug" required>
          <UInput v-model="state.slug" class="w-full" />
        </UFormField>

        <UFormField label="Батьківська категорія" name="parent_id" required>
          <USelect
            v-model.number="state.parent_id"
            :items="categoriesList"
            placeholder="Оберіть категорію зі списку"
            class="w-full"
          />
        </UFormField>

        <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-800">
          <UButton to="/admin/categories" color="neutral" variant="soft">Скасувати</UButton>
          <UButton type="submit" color="primary">Оновити категорію</UButton>
        </div>
      </UForm>
    </UCard>
  </div>
</template>
