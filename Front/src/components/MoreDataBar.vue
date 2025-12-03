<template>
  <v-card
    class="d-flex w-100 pa-4 justify-center align-center"
    style="height: 50px"
    color="primary"
  >
    <span class="white--text text-h6">
      Доп. данные к оп. {{ operationNumber }}:
      <span :class="{ change: changed }">
        {{ moreData }}
      </span>
    </span>
  </v-card>
</template>

<script>
export default {
  name: "MoreDataBar",
  props: {
    operationNumber: String | Number,
    moreData: String,
    changed: Boolean
  }
};
</script>

<style scoped>
.change {
  color: #ffee58 !important;
}
</style>
