<template>
    <div>
        <table class="table table-dark table-striped">
            <thead>
                <tr>
                    <th scope="col">Тип оборудования</th>
                    <th scope="col">Серийный номер</th>
                    <th scope="col">Примечание</th>
                </tr>
            </thead>
            <tbody v-if="equipments && equipments.data.length > 0">
                <tr v-for="(equipment, index) in equipments.data" :key="index">
                    <th>{{ equipment.type.name }}</th>
                    <td>{{ equipment.serial_number }}</td>
                    <td>{{ equipment.description ?? 'Отсутствует' }}</td>
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
            }
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
        }
    }
}
</script>

<style scoped>

</style>
