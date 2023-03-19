<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import {Head, InertiaLink} from '@inertiajs/inertia-vue3';

const props = defineProps({
    accounts: Array
})

</script>

<template>
    <Head title="Accounts"/>

    <BreezeAuthenticatedLayout>
        <div class="mx-auto my-6 max-w-screen-xl">
            <div class="my-3 flex justify-end">
                <InertiaLink :href="route('accounts.create')"
                             class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Create Account
                </InertiaLink>
            </div>
            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Name
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Country
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Town/City
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Phone
                                    </th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">View</span>
                                    </th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Edit</span>
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="account in accounts">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ account.name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ account.country }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ account.town_city }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ account.phone }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <InertiaLink :href="route('accounts.show', account.id)"
                                                     class="cursor-pointer text-indigo-600 hover:text-indigo-900">View
                                        </InertiaLink>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <InertiaLink :href="route('accounts.edit', account.id)"
                                                     class="cursor-pointer text-indigo-600 hover:text-indigo-900">Edit
                                        </InertiaLink>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>
