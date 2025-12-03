<template>
  <v-dialog :value="value" @input="$emit('input')" persistent width="400">
    <v-card color="primary" dark>
      <v-card-text class="text-center">
        Ожидайте, идет загрузка данных КНВ
        <v-progress-linear indeterminate color="white" class="mb-0" />
        <v-btn color="secondary" x-small class="mt-3 me-auto" @click="$emit('input')">
          Отменить
        </v-btn>
      </v-card-text>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: 'PageLoadingDialog',
  props: {
    value: Boolean,
  },
}
</script>