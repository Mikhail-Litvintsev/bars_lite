<template>
<button class="green-button">
    <slot/>
</button>
</template>

<script>
export default {
    name: "ButtonGreen"
}
</script>

<style scoped lang="scss">
.green-button {
    -webkit-transition-duration: 0.4s;
    transition-duration: 0.4s;
    border: 2px solid #146c43;
    border-radius: 12px;
    min-width: 100px;
    text-align: center;
    vertical-align: center;
}

.green-button:hover {
    background-color: #146c43;
    color: white;
    border: 2px solid #146c43;
    border-radius: 12px;
    min-width: 100px;
    text-align: center;
    vertical-align: center;
}
</style>
