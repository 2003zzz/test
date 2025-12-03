<template>
  <v-dialog :value="value" @input="$emit('input')" width="500">
    <v-form v-model="validation" @submit.prevent="">
      <v-card>
        <v-card-title>
          <span class="headline">
            Введите номер ссылочного ТП
          </span>
        </v-card-title>
        <v-text-field
          v-model="cipher_of_the_reference_tp"
          label="Номер ссылочного ТП"
          class="min-field mx-5"
          v-mask="'#####.#####'"
          :rules="[rules.cipher_main_td]"
          v-on:keyup.enter="onCreate"
        />
        <v-divider/>
        <v-card-actions>
          <v-btn color="secondary darken-1" text tabindex="-1" @click="$emit('input')">
            Закрыть
          </v-btn>
          <v-spacer/>
          <v-btn :disabled="!validation" color="primary" text @click="onCreate">
            Сохранить
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-form>
  </v-dialog>
</template>

<script>
export default {
  name: 'CreateReferenceTPDialog',
  props: {
    value: Boolean,
  },
  data: () => ({
    validation: false,
    cipher_of_the_reference_tp: "",
    rules: {
      cipher_main_td: value => /^\d{5}.\d{5}/.test(value) || "Неверное значение"
    },
  }),
  methods: {
    onCreate() {
      if (this.validation) {
        this.$emit('create', this.cipher_of_the_reference_tp);
      }
    },
  },
}
</script>