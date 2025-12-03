<template>
  <div
    class="v-card w-100 h-100 justify-start align-start d-flex flex-column"
    @click="GetFocusID('blur', null, null)"
  >
    <PageLoadingDialog v-model="pageLoading" @input="$router.back()" />

    <DeleteCardDialog
      v-model="deleteCardDialogShow"
      @input="onCancelDeleteCard"
      @delete="onDeleteCard"
    />

    <DeleteOperationDialog
      v-model="deleteOperationDialogShow"
      @delete="deleteSelectedOperation"
    />

    <CreateReferenceTPDialog
      v-model="referenceTPDialogShow"
      @create="CreateRefTD"
    />

    <UnsavedChangesDialog
      v-model="unsavedChangesDialogShow"
      :loading="saveAllDataLoading"
      @save="saveAndExit"
      @exit="exitWithoutSave"
    />

    <DuplicateOperationDialog
      v-model="duplicateOperationDialogShow"
      @duplicate="
        (cardIds) =>
          onDuplicateOperation({ operation: operationForDuplication, cardIds })
      "
    />

    <CardActionBar :unsaved-changes="unsave">
      <v-btn
        color="primary"
        class="me-3 mb-3"
        @click="pushEmptyOperationWithTP()"
        :disabled="isCardFunctioning"
      >
        <v-icon class="white--text text-h5">mdi-table-row-plus-after</v-icon
        >Добавить операцию
      </v-btn>
      <v-btn
        color="primary"
        class="me-3 mb-3"
        @click="CallRefTD()"
        :disabled="!isAddReferenceTPActive || isCardFunctioning"
      >
        <v-icon class="white--text text-h5">mdi-usb</v-icon>Добавить ссылочный
        ТП
      </v-btn>
      <v-btn
        color="primary"
        class="me-3 mb-3"
        :disabled="!isExitReferenceTPActive || isCardFunctioning"
        @click="OutRefTD(OperationIdFocus)"
      >
        <v-icon class="white--text text-h5">mdi-call-merge</v-icon>Выйти из
        ссылочного ТП
      </v-btn>
      <v-btn color="primary" class="me-3 mb-3" disabled>
        <v-icon class="white--text text-h5">mdi-check-all</v-icon>Перевести в
        учтенные
      </v-btn>
      <v-btn color="primary" class="me-3 mb-3" disabled>
        <v-icon class="white--text text-h5">mdi-cube-unfolded</v-icon>Развернуть
        КНВ
      </v-btn>
      <v-btn
        color="primary"
        class="me-3 mb-3"
        @click="selectStatus(3)"
        :disabled="isCardFunctioning"
      >
        <v-icon class="white--text text-h5">mdi-account-check</v-icon>Поставить
        на согласование
      </v-btn>
      <v-btn
        color="primary"
        class="me-3 mb-3"
        @click="onGetExcel()"
        :loading="getExcelLoading"
      >
        <v-icon class="white--text text-h5">mdi-file-chart</v-icon>Выгрузить
      </v-btn>
      <v-btn
        color="primary"
        class="me-3 mb-3"
        @click="saveAllData"
        :loading="saveAllDataLoading"
        :disabled="isCardFunctioning"
      >
        <v-icon class="white--text text-h5">mdi-content-save</v-icon>Сохранить
      </v-btn>
    </CardActionBar>
    <v-form class="w-100 pa-4 form" :disabled="isCardFunctioning">
      <v-row>
        <!-- Левая колонка -->
        <v-col cols="12" md="4">
          <v-text-field
            v-model="main.workshop"
            label="Цех"
            required
            dense
            disabled
          />
          <v-text-field
            v-model="main.party"
            label="Партия"
            dense
            type="number"
          />
          <v-text-field
            v-model="main.number_technological_notification"
            label="Технол. извещение"
            dense
          />
          <v-text-field
            v-model="main.cipher_main_TD"
            label="№ тех. док / ВТД"
            dense
          />

          <!-- Новые 4 поля в две строки -->
          <!-- <v-row dense class="mt-0">
            <v-col cols="6">
              <v-text-field
                v-model="main.new_field_1"
                label="Новое поле 1"
                dense
              />
            </v-col>
            <v-col cols="6">
              <v-text-field
                v-model="main.new_field_2"
                label="Новое поле 2"
                dense
              />
            </v-col>
          </v-row>
          <v-row dense class="mt-0">
            <v-col cols="6">
              <v-text-field
                v-model="main.new_field_3"
                label="Новое поле 3"
                dense
              />
            </v-col>
            <v-col cols="6">
              <v-text-field
                v-model="main.new_field_4"
                label="Новое поле 4"
                dense
              />
            </v-col>
          </v-row> -->
        </v-col>

        <!-- Центральная колонка -->
        <v-col cols="12" md="4">
          <v-text-field
            v-model="main.note"
            :counter="30"
            label="Примечание"
            dense
            disabled
          />
          <v-text-field
            v-model="main.designation"
            label="Индекс"
            dense
            class="mt-0"
            disabled
          />
          <v-text-field v-model="main.code_detail" label="Код" dense disabled />
        </v-col>

        <!-- Правая колонка - листы -->
        <v-col cols="12" md="1">
          <v-text-field v-model="sheet" label="Лист" dense disabled />
          <v-text-field v-model="sheets" label="Листов" dense disabled />
        </v-col>

        <!-- Колонка с трудоемкостью и расценками -->
        <v-col cols="8" md="3">
          <v-row dense>
            <v-col cols="4">
              <v-text-field
                v-model="main.labor_intensity"
                label="Трудоёмкость на ДСЕ / с Кзо"
                dense
                disabled
              />
            </v-col>
            <v-col cols="4">
              <v-text-field
                v-model="main.rate"
                label="Расценка (руб)/с Кзо"
                dense
                disabled
              />
            </v-col>
          </v-row>
          <v-row dense>
            <v-col cols="4">
              <v-text-field
                v-model="main.total_labor_intensity"
                label="Суммарная трудоёмкость по цехам"
                dense
                disabled
              />
            </v-col>
            <v-col cols="4">
              <v-text-field
                v-model="main.total_rate"
                label="Расценка / с Кзо"
                dense
                disabled
              />
            </v-col>
          </v-row>
        </v-col>
      </v-row>
    </v-form>
    <hr class="w-100" />

    <OperationForm
      ref="operationForm"
      :focus-index="OperationIdFocus"
      :disabled="isCardFunctioning"
      @added-new-operation="
        ({ index, operation }) => insertEmptyOperationWithTP(index)
      "
      @copied-operation="
        ({ index, operation }) => copyOperation({ index, item: operation })
      "
      @deleted-operation="
        ({ index, operation }) => onDeleteOperation(index, operation)
      "
      @focus="
        ({ index, operation }) =>
          GetFocusID('focus', index, operation.cipher_of_the_reference_tp)
      "
    />

    <CardStatusSelector :value="cardStatus" @input="selectStatus" />
  </div>
</template>

<script>
import { mapState, mapActions } from "vuex";
import { saveDownloadedFile } from "@/helpers/browser";
import { countCardPages } from "@/helpers/operation";
import Api from "@/services/api";
import PageLoadingDialog from "@/components/PageLoadingDialog";
import CardActionBar from "@/components/CardActionBar";
import CardStatusSelector from "@/components/CardStatusSelector";
import OperationForm from "./OperationForm";
import DeleteCardDialog from "./DeleteCardDialog";
import DeleteOperationDialog from "./DeleteOperationDialog";
import DuplicateOperationDialog from "./DuplicateOperationDialog";
import CreateReferenceTPDialog from "./CreateReferenceTPDialog";
import UnsavedChangesDialog from "./UnsavedChangesDialog";

export default {
  name: "EditCardPage",
  components: {
    OperationForm,
    PageLoadingDialog,
    DeleteCardDialog,
    DeleteOperationDialog,
    DuplicateOperationDialog,
    CreateReferenceTPDialog,
    UnsavedChangesDialog,
    CardStatusSelector,
    CardActionBar,
  },
  data: () => ({
    cardID: null,
    unsave: false,
    operationForDuplication: null,
    OperationIdFocus: null,
    deleteCardDialogShow: false,
    deleteOperationDialogShow: false,
    duplicateOperationDialogShow: false,
    unsavedChangesDialogShow: false,
    unsavedChangesLeavingRoute: null,
    cardStatus: null,
    cardPreviousStatus: null,
    referenceTPDialogShow: false,
    isAddReferenceTPActive: false,
    isExitReferenceTPActive: false,
  }),
  computed: {
    ...mapState("card", {
      main: (state) => state.card,
      operations: (state) => state.operations,
      pageLoading: (state) => state.isLoading,
      saveAllDataLoading: (state) => state.isSaving,
      getExcelLoading: (state) => state.status.excel.loading,
      operationError: (state) => state.status.operation.error,
    }),
    ...mapState("operation/focus", {
      focusedOperationIndex: (state) => state.focusedOperationIndex,
      focusedField: (state) => state.focusedField,
    }),
    isCardFunctioning() {
      return (
        this.cardStatus !== 2 &&
        this.cardStatus !== 5 &&
        this.cardStatus !== null
      );
    },
    sheet() {
      return 1;
    },
    sheets() {
      return countCardPages(this.operations);
    },
  },
  async mounted() {
    await this.fetchCardById(this.cardID);
    this.cardStatus = this.main.id_status;
    this.cardPreviousStatus = this.main.id_status;
    this.unsave = false;
    // Подключение горячих клавиш уже после загрузки страницы
    setTimeout(() => {
      document.addEventListener("keydown", this.winKeyDown);
    }, 0);
  },
  beforeDestroy() {
    document.removeEventListener("keydown", this.winKeyDown);
  },
  beforeRouteLeave(to, from, next) {
    if (this.unsave) {
      this.unsavedChangesLeavingRoute = to.fullPath;
      console.log(this.unsavedChangesLeavingRoute);
      this.unsavedChangesDialogShow = true;
      next(false);
    } else {
      next();
    }
  },
  watch: {
    main: {
      handler() {
        if (this.pageLoading === false) {
          this.unsave = true;
        }
      },
      deep: true,
    },
    operations: {
      handler() {
        if (this.pageLoading === false) {
          this.unsave = true;
        }
      },
      deep: true,
    },
  },
  created() {
    this.cardID = this.$route.params.id;
  },
  methods: {
    ...mapActions("card", [
      "fetchCardById",
      "saveCard",
      "deleteCard",
      "insertOperation",
      "insertEmptyOperationWithTP",
      "pushOperation",
      "pushEmptyOperation",
      "pushEmptyOperationWithTP",
      "copyOperation",
      "deleteOperation",
      "deleteOperationAndRenumerate",
      "getExcelFromCard",
      "copyPreviousOperationFields",
      "duplicateOperationToCards",
    ]),
    winKeyDown(event) {
      // Ctrl + S
      if (event.ctrlKey && event.keyCode === 83) {
        event.preventDefault();

        if (!this.saveAllDataLoading) {
          this.saveAllData();
        }
      }
      // F2
      if (event.keyCode === 113) {
        if (this.OperationIdFocus !== null) {
          this.insertEmptyOperationWithTP(this.OperationIdFocus);
        } else {
          this.pushEmptyOperationWithTP();
        }
      }
      // F8
      if (event.keyCode === 119) {
        this.showDeleteOperationDialog();
      }
      // F12
      if (event.keyCode === 123) {
        event.preventDefault();

        if (this.focusedField === null) return;

        if (this.focusedField === "hardware_cipher") {
          this.copyPreviousOperationFields({
            currentIndex: this.focusedOperationIndex,
            fields: ["hardware_cipher"],
          });
          return;
        }

        if (this.focusedField === "number_notification_sgt") {
          this.copyPreviousOperationFields({
            currentIndex: this.focusedOperationIndex,
            fields: ["number_notification_sgt"],
          });
          return;
        }

        this.operationForDuplication =
          this.operations[this.focusedOperationIndex];
        this.duplicateOperationDialogShow = true;
      }
    },
    async saveAndExit() {
      await this.saveAllData();
      this.$router.push({ path: this.unsavedChangesLeavingRoute });
    },
    exitWithoutSave() {
      this.unsave = false;
      this.$router.push({ path: this.unsavedChangesLeavingRoute });
    },
    async selectStatus(status) {
      if (status === 6) {
        this.cardStatus = 6;
        this.deleteCardDialogShow = true;
        return;
      }

      await Api.selectCardStatus(this.main.id_v01, status);

      this.cardStatus = status;
      this.cardPreviousStatus = status;
      this.$root.logger.log(
        `Статус редактируемой КНВ (id_v01: ${this.main.id_v01}) изменен: ${status}`
      );
    },
    async saveAllData() {
      this.$root.snackbar.close();

      const validation = await this.$refs.operationForm.validation();

      if (!validation) {
        this.$root.snackbar.showError(
          "В одной или нескольких операциях не заполнены обязательные поля!"
        );
        return false;
      }

      this.$root.snackbar.showInfo("Сохранение данных...");

      const isSaved = await this.saveCard();

      if (isSaved) {
        if (this.operations.length > 0 && this.cardStatus !== 2) {
          this.selectStatus(2);
        }

        this.unsave = false;

        this.$root.snackbar.showInfo("КНВ успешно сохранена!");
      }

      return isSaved;
    },
    showDeleteOperationDialog() {
      if (this.OperationIdFocus === null) return;

      this.deleteOperationDialogShow = true;
    },
    async onDeleteOperation(index, item) {
      await this.deleteOperation({ index, item });

      if (this.OperationIdFocus === index) {
        this.GetFocusID("blur", null, null);
      }

      this.$root.snackbar.showInfo("Операция была успешно удалена");
    },
    async deleteSelectedOperation() {
      if (this.OperationIdFocus === null) return;

      await this.deleteOperationAndRenumerate({
        index: this.OperationIdFocus,
        item: this.operations[this.OperationIdFocus],
      });
    },
    async onDeleteCard() {
      this.$root.snackbar.close();

      const isCardDeleted = await this.deleteCard(this.main.id_v01);

      if (isCardDeleted) {
        this.$root.snackbar.showInfo("КНВ успешно удалена");
        this.$router.push({ path: "/products" });
      } else {
        this.$root.snackbar.showError(
          "Ошибка удаления карты: Карта не найдена в базе данных"
        );
      }
    },
    onCancelDeleteCard() {
      this.cardStatus = this.cardPreviousStatus;
    },
    OutRefTD(index) {
      if (!this.operations[index + 1]) {
        this.pushEmptyOperation();
      }
      this.operations[index + 1].cipher_of_the_reference_tp = null;
      this.GetFocusID("focus", index, null);
    },
    GetFocusID(type, index, haveRTP) {
      this.OperationIdFocus = index;
      this.isExitReferenceTPActive = haveRTP !== null;
      this.isAddReferenceTPActive = type !== "blur";
    },
    CreateRefTD(enteredCipher) {
      const index = this.OperationIdFocus;
      const currentOperationCipher =
        this.operations[index].cipher_of_the_reference_tp;
      const mainCipher = this.main.cipher_main_TD.replace(/\s\/ (.*)/, "");

      if (
        (currentOperationCipher === null && enteredCipher === mainCipher) ||
        currentOperationCipher === enteredCipher
      ) {
        return;
      }

      this.referenceTPDialogShow = false;

      if (enteredCipher === mainCipher) {
        this.OutRefTD(index);
        return;
      }

      if (!this.operations[index + 1]) {
        this.pushEmptyOperationWithTP();
      }

      this.operations[this.OperationIdFocus + 1].cipher_of_the_reference_tp =
        enteredCipher;
    },
    CallRefTD() {
      this.referenceTPDialogShow = true;
    },
    async onGetExcel() {
      if ((await this.saveAllData()) === false) {
        return;
      }
      const data = await this.getExcelFromCard(this.cardID);

      saveDownloadedFile(data, `Карта норм времени.xlsx`);
    },
    async onDuplicateOperation({ operation, cardIds }) {
      const success = await this.duplicateOperationToCards({
        operation,
        cardIds,
      });
      if (success) {
        this.$root.snackbar.showInfo("Операция успешно скопирована");
      } else {
        this.$root.snackbar.showError(this.operationError);
      }
    },
  },
};
</script>

<style lang="scss" src="./EditCardPage.scss" />
