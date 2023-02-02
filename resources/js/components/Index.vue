<template>
    <div class="d-flex h-100 text-center text-white bg-dark">
        <div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
            <header class="mb-auto">
                <div>
                    <h3 class="float-md-start mb-0">k-telekom</h3>
                    <nav class="nav nav-masthead justify-content-center float-md-end">
                        <router-link v-if="token" :to="{ name: 'equipments.list' }" class="nav-link">Список оборудования</router-link>
                        <router-link v-if="!token" :to="{ name: 'user.login' }" class="nav-link">Авторизация</router-link>
                    </nav>
                </div>
            </header>

            <main class="px-3">
                <router-view></router-view>
                <notifications/>
            </main>
        </div>
    </div>
</template>

<script>
export default {
    name: "Index",

    data() {
        return {
            token: null,
        }
    },

    mounted() {
        this.token = localStorage.getItem('token')
    },

    updated() {
        this.token = localStorage.getItem('token')
    }
}
</script>

<style scoped>

</style>
