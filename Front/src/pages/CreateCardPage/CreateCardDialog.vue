<template>
  <v-dialog v-model="show" persistent max-width="600px">
    <v-form v-model="validation">
      <v-card>
        <v-card-title>
          <span class="headline">
            Параметры новой КНВ
          </span>
        </v-card-title>
        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12">
                <v-text-field
                  v-model="card.workshop"
                  label="Цех"
                  :rules="[
                    rules.required,
                    rules.onlyNumbers,
                    rules.greaterZero
                  ]"
                  required
                  type="number"
                  min="1"
                  v-on:keyup.enter="onCreateCard"
                />
              </v-col>
              <v-col cols="12">
                <v-text-field
                  v-model="card.designation"
                  label="Индекс"
                  required
                  disabled
                />
              </v-col>
              <v-col cols="12">
                <v-text-field
                  v-model="card.code_detail"
                  label="Код"
                  required
                  disabled
                />
              </v-col>
              <v-col cols="12">
                <v-text-field
                  v-model="card.cipher_main_td"
                  label="ТД"
                  :rules="[rules.required, rules.cipher_main_td]"
                  v-mask="'#####.#####'"
                  v-on:keyup.enter="onCreateCard"
                />
              </v-col>
            </v-row>

            <v-alert v-show="!validation" dense outlined type="error">
              Пожалуйста, заполните все поля!
            </v-alert>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-btn
            color="secondary darken-1"
            text
            tabindex="-1"
            @click="onCloseDialog"
          >
            Закрыть
          </v-btn>

          <v-spacer />

          <v-btn
            :disabled="!validation"
            color="blue darken-1"
            text
            :loading="loading"
            @click="onCreateCard"
          >
            Создать
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-form>
  </v-dialog>
</template>

<script>
import Api from "@/services/api";
export default {
  name: "CreateCardDialog",
  props: {
    show: Boolean
  },
  mounted() {
    console.log(this.card);
    this.card.designation = this.$route.query.p006;
    this.card.code_detail = this.$route.query.code_detail;
  },
  data: () => ({
    validation: false,
    loading: false,
    card: {
      workshop: "",
      designation: "",
      code_detail: "",
      cipher_main_td: ""
    },
    rules: {
      onlyNumbers: value => /^(\d+)$/.test(value) || "Только числовые значения",
      required: value => !!value || "Обязательное поле",
      greaterZero: value => value > 0 || "Неверное значение",
      cipher_main_td: value => /^\d{5}.\d{5}/.test(value) || "Неверное значение"
    }
  }),
  methods: {
    onCloseDialog() {
      this.$emit("close");
    },
    onCreateCard() {
      this.loading = true;
      Api.createCard({ ...this.card })
        .then(response => {
          if (response.data === "Карта существует") {
            this.$root.snackbar.showError(
              "Карточка с указанным Цехом и ТД уже существует."
            );
            this.$root.logger.log("Попытка создания уже существующей карточки");
            return;
          }

          if (response.data === "Карта не найдена") {
            this.$root.snackbar.showError(
              'Попытка создания КНВ для несуществующего изделия. Нажмите "Закрыть" на окне создания КНВ для перехода на страницу изделий.'
            );
            this.$root.logger.log(
              "Попытка создания КНВ для несуществующего изделия"
            );
            return;
          }
          this.$emit("create-card", {
            id_v01: response.data,
            card: this.card
          });
        })
        .catch(error => {
          if (error.response && error.response.status === 422) {
            const validationErrors = Object.values(
              error.response.data.message
            ).join("\n");
            this.$root.snackbar.showError(
              `Неверно заполнены поля: ${validationErrors}`
            );
          } else {
            this.$root.snackbar.showError(
              `Ошибка создания КНВ\nОбратитесь к разработчику или попробуйте еще раз`
            );
            this.$root.logger.log(`Ошибка при создании новой КНВ: ${error}`);
          }
        })
        .finally(() => (this.loading = false));
    }
  }
};
</script>
