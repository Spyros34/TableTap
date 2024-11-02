<template>
    <MainLayout :title="'Edit Profile'">
        <Head title="Edit Profile" />

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <!-- Profile Information Form -->
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <UpdateProfileInformationForm
                        :owner="$page.props.owner"
                        :shop="$page.props.shop"
                        :must-verify-email="$page.props.mustVerifyEmail"
                        :status="$page.props.status"
                        class="max-w-xl"
                    />
                </div>

                <!-- Update Password Form -->
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <UpdatePasswordForm class="max-w-xl" />
                </div>

                <!-- Delete User Form -->
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <DeleteUserForm class="max-w-xl" />
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<script setup>
import { Head, usePage } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';

const pageProps = usePage().props.value;
</script>
