<template>
    <div class="form-signin w-25 mx-auto" style="margin-top: 100px">
        <form>
            <h1 class="h3 mb-3 fw-normal">Авторизация</h1>

            <div v-if="errors_exist" class="alert alert-danger text-start" role="alert">
                {{ error_message }}
                <ul v-for="error in errors">
                    <li>{{ error.message }}</li>
                </ul>
            </div>

            <div class="form-floating">
                <input v-model="email" type="email" class="form-control bg-dark my-2 text-white" id="floatingInput"
                       placeholder="name@example.com" required>
                <label for="floatingInput">Email адрес</label>
            </div>
            <div class="form-floating">
                <input v-model="password" type="password" class="form-control bg-dark my-2 text-white"
                       id="floatingPassword" placeholder="Пароль" required>
                <label for="floatingPassword">Пароль</label>
            </div>

            <button @click.prevent="login" class="w-100 btn btn-lg btn-primary" type="submit">Sign in</button>
        </form>
    </div>
</template>

<script>
export default {
    name: "Login",

    mounted() {
        document.title = 'Авторизация';
    },

    data() {
        return {
            email: null,
            password: null,
            errors: [],
            errors_exist: false,
            error_message: null,
        }
    },

    methods: {
        login() {
            axios.get('/sanctum/csrf-cookie').then(response => {
                if(this.checkForm()) {
                    axios.post('/api/user/login', {
                        email: this.email,
                        password: this.password
                    }).then(response => {
                        localStorage.setItem('token', response.data.token)
                        this.$router.push({ name: "equipments.list" })
                    }).catch(err => {
                        this.errors_exist = true;

                        if (err.response.status === 422) {
                            this.error_message = err.response.data.message;
                            Object.values(error.response.data.errors).map(error => {
                                Object.values(error).map(message => {
                                    this.errors.push({ message: message })
                                })
                            })
                        }

                        if (err.response.status === 401) {
                            this.error_message = err.response.data.message;
                            this.errors = null;
                        }
                    })
                }
            })
        },

        checkForm() {
            this.errors_exist = false;
            this.errors = [];

            if (!this.email) {
                this.errors.push({ message: "Необходимо указать Email" });
            }

            if (!this.password) {
                this.errors.push({ message: "Необходимо указать пароль" });
            }

            if (this.errors.length > 0) {
                this.errors_exist = true;
                return false;
            }

            return true;
        },
    }
}
</script>

<style scoped>

</style>
