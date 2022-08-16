<template>
    <div style="position: fixed; right: 0; top: 0; padding: 10px; color: #fff; width: 50%; text-align: center">
        <button-green style="background-color: #1c7430" @click="saveEvent">Сохранить изменения</button-green>
    </div>
    <div style="margin-top: 50px">
        <pop-up v-if="typeof lpu[itemToDelete.itemIdx] !== 'undefined'" :show="showPopUp">
            <template v-slot:header>
                Требуется подтверждение:
            </template>
            <template v-slot:body>
                <div> Удалить {{
                        (itemToDelete.depIdx === null) ? lpu[itemToDelete.itemIdx]['name'] : lpu[itemToDelete.itemIdx]['departments'][itemToDelete.depIdx]['name'] ?? ''
                    }}?
                </div>
            </template>
            <template v-slot:footer>
                <div class="line">
                    <div style="display:inline-block; width: 40%">
                        <button-red @click="deleteItem()">да</button-red>
                    </div>
                    <div style="display:inline-block; width: 40%">
                        <button-green @click="showPopUp = false; itemToDelete = {itemIdx: null, depIdx: null}">нет
                        </button-green>
                    </div>
                </div>
            </template>
        </pop-up>
        <div class="line">
            <label v-if="this.version !== '' && this.version !== null">Версия*:</label>
            <label v-else style="color: #a52834">Версия*:</label>
            <input v-model="version"></div>
        <div class="line" style="border: 1px solid black;">
            <div @click="expandCollapseAll"
                 style="display:inline-block; width: 10%; text-align: center; border: 1px solid black;">
                <span v-if="collapsed"><b>Развернуть все</b></span>
                <span v-else><b>Cвернуть все</b></span></div>
            <div style="display:inline-block; width: 25%; text-align: center; border: 1px solid black;">
                <b>Наименование</b></div>
            <div style="display:inline-block; width: 25%; text-align: center; border: 1px solid black;"><b>Адрес</b>
            </div>
            <div style="display:inline-block; width: 20%; text-align: center; border: 1px solid black;"><b>Телефон</b>
            </div>
            <div style="display:inline-block; width: 10%; text-align: center; border: 1px solid black;">Удалить</div>
            <div style="display:inline-block; width: 10%; text-align: center; border: 1px solid black;">Добавить</div>
        </div>
        <div class="line" style="border: 1px solid black;">
            <div colspan="5" @click="addParent" style="text-align: center; background-color: #d1e7dd; border: 0; ">
                Добавить учреждение +
            </div>
        </div>
        <div v-for="(item, idx) in lpu">
            <div class="line" style="border: 1px solid black;">
                <div
                    style="display:inline-block; width: 10%; text-align: center; min-height: 100%; vertical-align: bottom"
                    ref="expandable"
                    v-if="!checkExpanded(item) && typeof item.departments !== 'undefined'"
                    @click="expand(idx)">+
                </div>
                <div style="display:inline-block; width: 10%; text-align: center;; vertical-align: bottom"
                     v-else-if="typeof item.departments === 'undefined'"></div>
                <div style="display:inline-block; width: 10%; text-align: center;"
                     ref="collapseable" v-else
                     @click="collapse(item, idx)">-
                </div>
                <div style="display:inline-block; width: 25%;">
                    <input style="width: 100%;" v-model="item.name">
                </div>
                <div style="display:inline-block; width: 25%;">
                    <input style="width: 100%" v-model="item.address">
                </div>
                <div style="display:inline-block; width: 20%;">
                    <input style="width: 100%" v-model="item.phone">
                </div>
                <div @click="askDeleteItem(idx)" style="display:inline-block; width: 10%; text-align: center;"><b
                    style="color: red">X</b></div>
                <div style="display:inline-block; width: 10%; text-align: center;">
                    <button-green style="width: 100%" v-if="typeof item.departments !== 'undefined'"
                                  @click="addChild(idx)">
                        + подразделение
                    </button-green>
                </div>
            </div>
            <div v-if="checkExpanded(item) && typeof item.departments !== 'undefined'"
                 v-for="(dep, depIdx) in item.departments">
                <div class="line"
                     style="border-bottom: 1px solid black;border-left: 1px solid black;border-right: 1px solid black;">
                    <div style="display:inline-block; width: 10%; text-align: center;"></div>
                    <div style="display:inline-block; width: 25%;">
                        <input style="width: 100%" v-model="dep.name">
                    </div>
                    <div style="display:inline-block; width: 25%;">
                        <input style="width: 100%" v-model="dep.address">
                    </div>
                    <div style="display:inline-block; width: 20%;">
                        <input style="width: 100%" v-model="dep.phone">
                    </div>
                    <div @click="askDeleteItem(idx, depIdx)"
                         style="display:inline-block; width: 10%; text-align: center;">
                        <b
                            style="color: red">X</b></div>
                    <div style="display:inline-block; width: 10%; text-align: center;"></div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import PopUp from "../../components/UI/PopUp";
import ButtonRed from "../../components/UI/ButtonRed";
import ButtonGreen from "../../components/UI/ButtonGreen";

const lpuEmptyItem = JSON.stringify({
    expanded: false,
    name: '',
    address: '',
    phone: '',
    departments: []
});
const lpuEmptyDepartment = JSON.stringify({
    name: '',
    address: '',
    phone: '',
});
export default {
    name: "LpuForm",
    components: {ButtonGreen, ButtonRed, PopUp},
    props: {
        file: null,
        version: null
    },
    emits: ['save'],
    data() {
        return {
            collapsed: false,
            lpu: [JSON.parse(lpuEmptyItem)],
            itemToDelete: {itemIdx: null, depIdx: null},
            showPopUp: false,
        }
    },
    methods: {
        expand(idx) {
            this.lpu[idx]['expanded'] = true;
        },
        checkExpanded(item) {
            if (typeof item.expanded !== "undefined") {
                return item.expanded;
            }
            return false;
        },
        collapse(item, idx) {
            this.lpu[idx]['expanded'] = false;
        },
        expandCollapseAll() {
            if (this.collapsed) {
                if (typeof this.$refs.expandable !== 'undefined') {
                    this.$refs.expandable.forEach((value) => {
                        value.click()
                    });
                }
                this.collapsed = false;
            } else {
                if (typeof this.$refs.collapseable !== 'undefined') {
                    this.$refs.collapseable.forEach((value) => {
                        value.click()
                    });
                }
                this.collapsed = true;
            }
        },
        addParent() {
            this.lpu.unshift(JSON.parse(lpuEmptyItem))
        },
        addChild(idx) {
            this.lpu[idx].departments.push(JSON.parse(lpuEmptyDepartment))
            this.lpu[idx]['expanded'] = true;
        },
        askDeleteItem(itemIdx, depIdx = null) {
            this.itemToDelete = {itemIdx: itemIdx, depIdx: depIdx};
            this.showPopUp = true;
        },
        deleteItem() {
            if (this.itemToDelete.depIdx === null) {
                this.lpu.splice(this.itemToDelete.itemIdx, 1);
            } else {
                this.lpu[this.itemToDelete.itemIdx]['departments'].splice(this.itemToDelete.depIdx, 1);
            }
            this.showPopUp = false;
            this.itemToDelete = {itemIdx: null, depIdx: null};
        },
        setLpu() {
            let file = JSON.parse(this.file);
            if (file !== null) {
                this.lpu = file;
            }
        },
        saveEvent() {
            if (!(this.version !== '' && this.version !== null)) {
                alert('Необходимо записать версию!')
                return;
            }
            if(typeof this.lpu[0] === 'undefined') {
                alert('Необходимо добавить учреждение!')
                return;
            }
            this.$emit('save', {lpu: this.lpu, version:this.version});
        }
    },
    mounted() {
        this.setLpu();
    },
    watch: {
        file: {
            handler() {
                this.setLpu();
            },
            deep: true
        }
    }
}
</script>

<style scoped lang="scss">
th, td {
    border: 1px solid black;
    border-collapse: collapse;
}

.vl {
    border-left: 6px solid green;

}
</style>
