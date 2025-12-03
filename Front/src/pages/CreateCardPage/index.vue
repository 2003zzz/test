<template>
  <div class="v-card w-100 h-100 justify-start align-start d-flex flex-column">
    <CreateCardDialog
      :show="true"
      @create-card="onCreateCard"
      @close="goPreviousPage"
    />
  </div>
</template>

<script>
import Api from "@/services/api";
import { Card, Operation } from "@/models";
import CreateCardDialog from "./CreateCardDialog";

export default {
  name: "CreateCardPage",
  components: {
    CreateCardDialog
  },
  data: () => ({
    cardID: null,
    main: new Card(),
    operations: []
  }),
  methods: {
    goPreviousPage() {
      window.history.length > 1
        ? window.history.back()
        : (window.location.href = "/");
    },
    async onCreateCard({ id_v01, card }) {
      this.cardID = id_v01;
      this.main.id_v01 = id_v01;
      if (this.$route.query.copy) {
        await this.saveCopiedCard();
        this.$root.logger.log(
          `Скопирована КНВ (ID: ${this.$route.query.copy}), новая КНВ (ID: ${this.cardID})`
        );
      } else {
        this.main = new Card({ ...card, id_v01: this.cardID });
        this.$root.logger.log(`Создана новая КНВ: ${this.cardID}`);
      }
      this.$router.push({ path: `/products/edit/${this.cardID}` });
    },
    async saveCopiedCard() {
      try {
        const response = await Api.fetchCardById(this.$route.query.copy);
        const { card, operations } = response.data;

        this.main = new Card(card);
        this.selectStatus(2);

        this.operations = [];

        if (operations.length > 0) {
          operations.forEach(operation => {
            operation.id_v02 = null;
            this.operations.push(Operation.createOperationForPush(operation));
          });
        }

        setTimeout(() => {
          this.saveCopiedData();
        }, 100);
      } catch (error) {
        this.$root.snackbar.showError(
          `Не удалось получить информацию КНВ\nОбратитесь к разработчику или попробуйте еще раз`
        );
      }
    },
    async saveCopiedData() {
      this.$root.snackbar.showInfo("Сохранение копии...");
      try {
        const response = await Api.saveCard({
          card: this.main,
          operations: this.operations.map(Operation.toRequestFormat)
        });
        const updatedOperations = response.data;

        if (Array.isArray(updatedOperations)) {
          this.operations.forEach((operation, index) => {
            operation.id_v02 = updatedOperations[index];
          });
        }

        this.$root.snackbar.showInfo("КНВ успешно сохранена!");
        this.$root.logger.log(
          `Скопированная КНВ (ID: ${this.cardID}) сохранена | ${updatedOperations}`
        );
      } catch (error) {
        this.$root.snackbar.showError(
          "Данные не были сохранены\nОбратитесь к разработчику или попробуйте еще раз"
        );
        this.$root.logger.log(
          `Ошибка при сохранении копируемой КНВ (ID: ${this.cardID}): ${error}`
        );
      }
    },
    async selectStatus(status) {
      await Api.selectCardStatus(this.cardID, status);
      this.$root.logger.log(
        `Статус редактируемой КНВ (ID: ${this.cardID}) изменен: ${status}`
      );
    }
  }
};
</script>
