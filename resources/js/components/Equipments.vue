<template>
    <div>
        <div class="alert alert-danger text-start" role="alert" :class="{ shake: disabled }" v-if="disabled">
            {{ error_message }}
            <ul v-for="error in errors">
                <li>{{ error.message }}</li>
            </ul>
        </div>
        <input type="text" class="bg-dark text-white form-control" v-model="search" @input="searchEquipment" placeholder="Поиск по серийному номеру/примечанию">
        <table class="table table-dark table-striped">
            <thead>
                <tr>
                    <th scope="col">Тип оборудования</th>
                    <th scope="col">Серийный номер</th>
                    <th scope="col">Примечание</th>
                </tr>
            </thead>
            <tbody v-if="equipments">
                <tr v-for="(equipment, index) in equipments.data" :key="index">
                    <th>
                        <input class="bg-dark text-white form-control text-center" type="text" v-model="equipment.type.name">
                    </th>
                    <td>
                        <input class="bg-dark text-white form-control text-center" type="text" v-model="equipment.serial_number">
                    </td>
                    <td>
                        <input class="bg-dark text-white form-control text-center" type="text" v-model="equipment.description">
                    </td>
                    <td>
                        <button type="button" class="btn btn-light" @click="editEquipment(equipment)">Сохранить</button>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger" @click="deleteEquipment(equipment)">Удалить</button>
                    </td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr>
                    <td class="text-center" colspan="3">Записи не найдены</td>
                </tr>
            </tbody>
        </table>
        <pagination align="center" :data="equipments" @pagination-change-page="getEquipments" class="bg-dark">
            <span slot="prev-nav">Назад</span>
            <span slot="next-nav">Далее</span>
            <span slot="sr-only">Текущее</span>
        </pagination>
    </div>
</template>

<script>
import pagination from 'laravel-vue-pagination'
export default {
    name: "Equipments",

    components:{
        pagination
    },

    data() {
        return {
            equipments: {
                type: Object,
                default: null,
            },
            disabled: false,
            errors: [],
            search: null,
            page: null,
        }
    },

    mounted() {
        document.title = 'Список оборудования';
        this.getEquipments();
    },

    methods: {
        async getEquipments(page = 1) {
            await axios.get("/api/equipment?page=" + page).then(response => {
                this.equipments = response.data
            })
        },

        async editEquipment(equipment) {
            if (this.checkForm(equipment)) {
                await axios.put('/api/equipment/' + equipment.id, {
                    id: equipment.id,
                    type_id: equipment.type.id,
                    serial_number: equipment.serial_number,
                    description: equipment.description
                }).then(response => {
                    this.$notify({
                        title: 'Сохранение оборудования',
                        text: response.data.message,
                        type: 'success',
                    });
                }).catch(error => {
                    if (error.response.status === 422) {
                        Object.values(error.response.data.errors).map(error => {
                            Object.values(error).map(message => {
                                this.errors.push({ message: message })
                            })
                        })
                        this.showAlert()
                    }

                    if (error.response.status === 404) {
                        this.$notify({
                            title: 'Сохранение оборудования',
                            text: error.data.message,
                            type: 'error',
                        });
                    }
                })
            }
        },

        async deleteEquipment(equipment) {
            await axios.delete('/api/equipment/' + equipment.id).then(response => {
                this.$notify({
                    title: 'Удаление оборудования',
                    text: response.data.message,
                    type: 'success',
                });

                this.getEquipments()
            }).catch(error => {
                if (error.response.status === 404) {
                    this.$notify({
                        title: 'Удаление оборудования',
                        text: error.data.message,
                        type: 'error',
                    });
                }

                this.showAlert()
            })
        },

        async searchEquipment() {
            await axios.get("/api/equipment?page=1" + this.page, {
                params: {
                    query: this.search
                }
            }).then(response => {
                this.equipments = response.data
            })
        },

        showAlert() {
            this.disabled = true
            setTimeout(() => {
                this.disabled = false
            }, 7000)
        },

        checkForm(equipment) {
            this.errors_exist = false;
            this.errors = [];

            if (equipment.type.name === '') {
                this.errors.push({ message: "Необходимо указать тип оборудования" });
            }

            if (equipment.serial_number === '') {
                this.errors.push({ message: "Необходимо указать серийный номер оборудования" });
            }

            if (this.errors.length > 0) {
                this.errors_exist = true;
                this.showAlert()
                return false;
            }

            return true;
        },
    }
}
</script>

<style scoped>
.shake {
    animation: shake 0.82s cubic-bezier(0.36, 0.07, 0.19, 0.97) both;
    transform: translate3d(0, 0, 0);
}

@keyframes shake {
    10%,
    90% {
        transform: translate3d(-1px, 0, 0);
    }

    20%,
    80% {
        transform: translate3d(2px, 0, 0);
    }

    30%,
    50%,
    70% {
        transform: translate3d(-4px, 0, 0);
    }

    40%,
    60% {
        transform: translate3d(4px, 0, 0);
    }
}
</style>
