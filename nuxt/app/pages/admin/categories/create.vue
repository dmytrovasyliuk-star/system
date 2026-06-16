<script setup lang="ts">
import { z } from 'zod'
import type { FormSubmitEvent } from '#ui/types'

const router = useRouter()
const toast = useToast()

const { data: responseData } = await useFetch<any>('http://localhost/api/admin/blog/categories', {
  mode: 'cors',
  server: false
})

const categoriesList = computed(() => {
  const rawArray = responseData.value?.data || []
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

async function onSubmit(event: FormSubmitEvent<Schema>) {
  try {
    await $fetch('http://localhost/api/admin/blog/categories', {
      method: 'POST',
      body: event.data,
      mode: 'cors'
    })

    toast.add({ title: 'Успішно', description: 'Категорію створено', color: 'success' })
    router.push('/admin/categories')
  } catch (error: any) {
    console.error(error)

    const backendMessage = error.data?.message || 'Не вдалося зберегти категорію.'
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
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Створення нової категорії</h1>
    </div>

    <UCard>
      <UForm :schema="schema" :state="state" class="space-y-6" @submit="onSubmit">

        <UFormField label="Назва категорії" name="title" required>
          <UInput v-model="state.title" placeholder="Наприклад: Програмування" class="w-full" />
        </UFormField>

        <UFormField label="Slug (URL)" name="slug" required>
          <UInput v-model="state.slug" placeholder="Наприклад: programming" class="w-full" />
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
          <UButton type="submit" color="primary">Зберегти категорію</UButton>
        </div>
      </UForm>
    </UCard>
  </div>
</template>
