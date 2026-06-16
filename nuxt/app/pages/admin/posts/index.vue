<script setup lang="ts">
interface Post {
  id: number
  title: string
  slug: string
  category?: { title: string }
}

const route = useRoute()
const toast = useToast()

const { data: postsData, refresh, pending } = await useFetch<any>('http://localhost/api/admin/blog/posts', {
  mode: 'cors',
  server: false
})

const rows = computed(() => {
  return postsData.value?.data || []
})

const columns = [
  { id: 'id', accessorKey: 'id', header: 'ID' },
  { id: 'title', accessorKey: 'title', header: 'Заголовок статті' },
  { id: 'category', accessorKey: 'category.title', header: 'Категорія' },
  { id: 'actions', header: 'Дії' }
]

const items = (row: Post) => [
  [
    {
      label: 'Редагувати',
      icon: 'i-heroicons-pencil-square-20-solid',
      to: `/admin/posts/${row.id}/edit`
    },
    {
      label: 'Видалити',
      icon: 'i-heroicons-trash-20-solid',
      onSelect: () => deletePost(row.id)
    }
  ]
]

async function deletePost(id: number) {
  if (!confirm('Ви впевнені, що хочете видалити цю статтю?')) return

  try {
    await $fetch(`http://localhost/api/admin/blog/posts/${id}`, {
      method: 'DELETE',
      mode: 'cors'
    })

    toast.add({ title: 'Успішно', description: 'Статтю видалено', color: 'success' })
    refresh()
  } catch (error) {
    console.error(error)
    toast.add({ title: 'Помилка', description: 'Не вдалося видалити статтю', color: 'error' })
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

      <UButton to="/admin/posts/create" color="primary" icon="i-heroicons-plus">
        Додати статтю
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
