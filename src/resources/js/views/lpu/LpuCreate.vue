<template>
    <main-layout>
        <lpu-form @save="saveEvent($event)" :file="null" :version="null"></lpu-form>
    </main-layout>
</template>
<script>
import LpuForm from "../../components/lpu/LpuForm";
import MainLayout from "../layouts/MainLayout";

export default {
    name: "LpuCreate",
    components: {LpuForm, MainLayout},
    data() {
        return {
            lpu: [],
            version: ''
        }
    },
    methods: {
        saveEvent($event) {
            this.lpu = $event.lpu;
            this.version = $event.version;
            this.lpuStore();
        },
        async lpuStoreRequest() {
            let token = document.head.querySelector('meta[name="csrf-token"]');
            if (token) {
                window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
            }
            return await axios.post('/api/v1/lpu/store', {lpu: this.lpu, version:this.version});
        },
        lpuStore() {
            this.lpuStoreRequest().then(response => {
                if (response.data.success === true) {
                    window.location.href = '/lpu/' + response.data.data.id;
                } else {
                    console.log('LpuCreate error lpu store ', response.data)
                }
            })
        }
    }
}
</script>

<style scoped lang="scss">
</style>
