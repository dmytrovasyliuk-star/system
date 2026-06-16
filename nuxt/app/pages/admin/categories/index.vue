<script setup lang="ts">
interface Category {
  id: number
  title: string
  slug: string
}

const route = useRoute()
const toast = useToast()

const { data: categoriesData, refresh, pending } = await useFetch<any>('http://localhost/api/admin/blog/categories', {
  mode: 'cors',
  server: false
})

const rows = computed(() => {
  return categoriesData.value?.data || []
})

const columns = [
  { id: 'id', accessorKey: 'id', header: 'ID' },
  { id: 'title', accessorKey: 'title', header: 'Назва категорії' },
  { id: 'slug', accessorKey: 'slug', header: 'Slug' },
  { id: 'actions', header: 'Дії' }
]

const items = (row: Category) => [
  [
    {
      label: 'Редагувати',
      icon: 'i-heroicons-pencil-square-20-solid',
      to: `/admin/categories/${row.id}/edit`
    },
    {
      label: 'Видалити',
      icon: 'i-heroicons-trash-20-solid',
      onSelect: () => deleteCategory(row.id)
    }
  ]
]

async function deleteCategory(id: number) {
  if (!confirm('Ви впевнені, що хочете видалити цю категорію?')) return

  try {
    await $fetch(`http://localhost/api/admin/blog/categories/${id}`, {
      method: 'DELETE',
      mode: 'cors'
    })

    toast.add({ title: 'Успішно', description: 'Категорію видалено', color: 'success' })
    refresh()
  } catch (error) {
    console.error(error)
    toast.add({ title: 'Помилка', description: 'Не вдалося видалити категорію', color: 'error' })
  }
}
</script>

<template>
  <div class="p-8">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
      <div class="flex items-center gap-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Управління блогом</h1>

        <UButtonGroup size="md" variant="outline">
          <UButton
            to="/admin/posts"
            icon="i-heroicons-document-text"
            :color="route.path.startsWith('/admin/posts') ? 'primary' : 'neutral'"
            :variant="route.path.startsWith('/admin/posts') ? 'solid' : 'outline'"
          >
            Статті
          </UButton>
          <UButton
            to="/admin/categories"
            icon="i-heroicons-folder"
            :color="route.path.startsWith('/admin/categories') ? 'primary' : 'neutral'"
            :variant="route.path.startsWith('/admin/categories') ? 'solid' : 'outline'"
          >
            Категорії
          </UButton>
        </UButtonGroup>
      </div>

      <UButton to="/admin/categories/create" color="primary" icon="i-heroicons-plus">
        Додати категорію
      </UButton>
    </div>

    <UCard>
      <UTable :data="rows" :columns="columns" :loading="pending">
        <template #actions-cell="{ row }">
          <UDropdownMenu :items="items(row.original)">
            <UButton color="neutral" variant="ghost" icon="i-heroicons-ellipsis-horizontal-20-solid" />
          </UDropdownMenu>
        </template>
      </UTable>
    </UCard>
  </div>
</template>
