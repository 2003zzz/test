<template>
  <div class="w-100">
    <v-select
      class="pl-4"
      v-model="main.version"
      :items="versions"
      :item-text="item => `${item.version} (${item.created})`"
      :item-value="item => item.version"
      label="Выберите версию КНВ"
      @change="value => $emit('change', value)"
    />
    <v-form class="w-100 pa-4 form" ref="form" readonly>
      <v-card class="px-3">
        <v-row>
          <v-col cols="12" md="3">
            <v-text-field
              :value="main.card.workshop"
              :rules="[sameAsCompared('workshop')]"
              label="Цех"
            />
            <v-text-field
              :value="main.card.party"
              :rules="[sameAsCompared('party')]"
              label="Партия"
            />
            <v-text-field
              :value="main.card.number_technological_notification"
              :rules="[sameAsCompared('number_technological_notification')]"
              label="Технол. извещение"
            />
            <v-text-field
              :value="main.card.cipher_main_TD"
              :rules="[sameAsCompared('cipher_main_TD')]"
              label="№ тех. док / ВТД"
              v-mask="'#####.##### / #'"
            />
            <v-text-field
              :value="main.card.service_number"
              :rules="[sameAsCompared('service_number')]"
              label="Последний редактор"
            />
          </v-col>
          <v-col cols="12" md="6">
            <v-text-field :value="main.card.note" label="Примечание" />
            <v-text-field :value="main.card.designation" label="Индекс" />
            <v-text-field :value="main.card.code_detail" label="Код" />
            <v-text-field
              :value="status"
              :rules="[sameAsCompared('id_status')]"
              label="Статус КНВ"
            />
            <v-text-field
              :value="main.card.notification_number_ott"
              :rules="[sameAsCompared('notification_number_ott')]"
              label="Номер извещения ОТТ"
            />
          </v-col>
          <v-col cols="12" md="3">
            <v-text-field :value="sheet" label="Лист" />
            <v-text-field :value="sheets" label="Листов" />
            <v-text-field
              :value="main.card.number_of_parts_in_batch"
              :rules="[sameAsCompared('number_of_parts_in_batch')]"
              label="Количество деталей в партии"
            />
            <v-text-field
              :value="main.card.validity_period_norms"
              :rules="[sameAsCompared('validity_period_norms')]"
              label="Срок действия норм"
            />
            <v-text-field
              :value="main.card.minimum_number_blanks"
              :rules="[sameAsCompared('minimum_number_blanks')]"
              label="Минимальное число заготовок"
            />
          </v-col>
        </v-row>
      </v-card>
      <v-card
        v-for="(operation, index) of main.operations"
        :key="operation.UID"
      >
        <v-card
          class="mt-3 px-6 py-3"
          :class="{
            deleted: isDeletedOperation(operation),
            new: isNewOperation(operation)
          }"
        >
          <v-row dense>
            <v-col>
              <v-text-field
                :value="operation.MoreDataShortString"
                :background-color="
                  isOperationParameterDiffers(operation, 'MoreDataShortString')
                    ? 'warning lighten-4'
                    : ''
                "
                label="Доп. данные"
              />
            </v-col>
            <v-col>
              <v-text-field
                :value="operation.end_to_end_operation_number"
                :background-color="
                  isOperationParameterDiffers(
                    operation,
                    'end_to_end_operation_number'
                  )
                    ? 'warning lighten-4'
                    : ''
                "
                label="№ скв. опер."
              />
            </v-col>
            <v-col>
              <v-text-field
                :value="operation.operation_number"
                :background-color="
                  isOperationParameterDiffers(operation, 'operation_number')
                    ? 'warning lighten-4'
                    : ''
                "
                label="№ операции"
              />
            </v-col>
            <v-col md="1">
              <v-text-field
                :value="operation.cipher_of_the_operation.p018"
                :background-color="
                  isOperationParameterDiffers(
                    operation,
                    'cipher_of_the_operation.p018'
                  )
                    ? 'warning lighten-4'
                    : ''
                "
                :hint="operation.cipher_of_the_operation.p014"
                persistent-hint
                label="Шифр опер."
              />
            </v-col>
            <v-col md="1">
              <v-text-field
                :value="operation.cipher_of_the_profession.profcode70"
                :background-color="
                  isOperationParameterDiffers(
                    operation,
                    'cipher_of_the_profession.profcode70'
                  ) ||
                  isOperationParameterDiffers(
                    operation,
                    'cipher_of_the_profession.type_of_profession_reference_book'
                  )
                    ? 'warning lighten-4'
                    : ''
                "
                :hint="operation.cipher_of_the_profession.snm_as_scaption"
                persistent-hint
                label="Профессия"
              />
            </v-col>
            <v-col>
              <v-text-field
                :value="operation.category_of_work"
                :background-color="
                  isOperationParameterDiffers(operation, 'category_of_work')
                    ? 'warning lighten-4'
                    : ''
                "
                label="Р-д"
              />
            </v-col>
            <v-col md="1">
              <v-text-field
                :value="operation.hardware_cipher.p501"
                :background-color="
                  isOperationParameterDiffers(operation, 'hardware_cipher.p501')
                    ? 'warning lighten-4'
                    : ''
                "
                :hint="operation.hardware_cipher.p451"
                persistent-hint
                label="Шифр оборудования"
              />
            </v-col>
            <v-col>
              <v-text-field
                :value="operation.type_of_norms"
                :background-color="
                  isOperationParameterDiffers(operation, 'type_of_norms')
                    ? 'warning lighten-4'
                    : ''
                "
                label="Вид норм"
              />
            </v-col>
            <v-col>
              <v-text-field
                :value="operation.code_of_the_tariff_grid"
                :background-color="
                  isOperationParameterDiffers(
                    operation,
                    'code_of_the_tariff_grid'
                  )
                    ? 'warning lighten-4'
                    : ''
                "
                label="Код т.с."
              />
            </v-col>
            <v-col>
              <v-text-field
                :value="operation.unit_of_the_rationong"
                :background-color="
                  isOperationParameterDiffers(
                    operation,
                    'unit_of_the_rationong'
                  )
                    ? 'warning lighten-4'
                    : ''
                "
                label="Ед. норм."
              />
            </v-col>
            <v-col>
              <v-text-field
                :value="operation.time_rate_is_paid"
                :background-color="
                  isOperationParameterDiffers(operation, 'time_rate_is_paid')
                    ? 'warning lighten-4'
                    : ''
                "
                label="Время"
              />
            </v-col>
            <v-col>
              <v-text-field
                :value="operation.launch_ratio"
                :background-color="
                  isOperationParameterDiffers(operation, 'launch_ratio')
                    ? 'warning lighten-4'
                    : ''
                "
                label="К-т зап."
              />
            </v-col>
            <v-col>
              <v-text-field
                :value="operation.number_notification_sgt"
                :background-color="
                  isOperationParameterDiffers(
                    operation,
                    'number_notification_sgt'
                  )
                    ? 'warning lighten-4'
                    : ''
                "
                label="№ извещ. СГТ"
              />
            </v-col>
          </v-row>
        </v-card>
        <MoreDataBar
          v-if="operation.countMoreDataFields() > 1"
          :operation-number="operation.end_to_end_operation_number"
          :more-data="operation.MoreDataLongString"
          :changed="
            isOperationParameterDiffers(operation, 'MoreDataLongString')
          "
        />
        <NextTPBar
          v-if="
            index + 1 !== main.operations.length &&
              operation.cipher_of_the_reference_tp !==
                main.operations[index + 1].cipher_of_the_reference_tp
          "
          :next-process="
            main.operations[index + 1].cipher_of_the_reference_tp ||
              main.card.cipher_main_TD.replace(/\s\/ (.*)/, '')
          "
          :changed="isChangedTP(operation, index + 1)"
        />
      </v-card>
    </v-form>
  </div>
</template>

<script>
import { mapState } from "vuex";
import { getObjectPropertyRecursive } from "@/helpers/object";
import { countCardPages } from "@/helpers/operation";
import MoreDataBar from "@/components/MoreDataBar";
import NextTPBar from "@/components/NextTPBar";

export default {
  name: "ArchiveCardForm",
  components: {
    MoreDataBar,
    NextTPBar
  },
  props: {
    main: Object,
    compared: Object
  },
  watch: {
    main() {
      if (this.canCompareVersions) {
        this.$refs.form.validate();
      }
    },
    compared() {
      if (this.canCompareVersions) {
        this.$refs.form.validate();
      }
    }
  },
  computed: {
    ...mapState("archive", {
      versions: state => state.versions
    }),
    ...mapState("status", {
      statuses: state => state.statuses
    }),
    sheet() {
      return 1;
    },
    sheets() {
      return countCardPages(this.main.operations);
    },
    status() {
      const status = this.statuses.find(
        status => status.id_status === this.main.card.id_status
      );
      return status ? status.value : null;
    },
    thisVersionOlder() {
      return this.main.version > this.compared.version;
    },
    canCompareVersions() {
      return (
        !this.main.loading &&
        !this.compared.loading &&
        this.main.version !== null &&
        this.compared.version !== null &&
        this.main.version !== this.compared.version
      );
    }
  },
  methods: {
    isDeletedOperation(origin) {
      if (!this.canCompareVersions) return false;
      const operation = this.compared.operations.find(
        operation => operation.id_v02 === origin.id_v02
      );
      return this.main.version < this.compared.version && !operation;
    },
    isNewOperation(origin) {
      if (!this.canCompareVersions) return false;
      const operation = this.compared.operations.find(
        operation => operation.id_v02 === origin.id_v02
      );
      return this.main.version > this.compared.version && !operation;
    },
    sameAsCompared(field) {
      return () => {
        if (!this.canCompareVersions) return true;
        if (!this.main.card[field] && !this.compared.card[field]) return true;
        if (!this.main.card[field] || !this.compared.card[field]) return false;
        return (
          this.main.card[field].toString() ===
          this.compared.card[field].toString()
        );
      };
    },
    isOperationParameterDiffers(origin, field) {
      if (!this.canCompareVersions) return false;
      const operation = this.compared.operations.find(
        operation => operation.id_v02 === origin.id_v02
      );
      if (!operation) return false;
      const originField = getObjectPropertyRecursive(origin, field);
      const operationField = getObjectPropertyRecursive(operation, field);

      if (!originField && !operationField) return false;
      if (!originField || !operationField) return true;
      return originField.toString() !== operationField.toString();
    },
    isChangedTP(origin, originNextIndex) {
      if (!this.canCompareVersions) return false;
      const nextOrigin = this.main.operations[originNextIndex];
      const originTP = origin["cipher_of_the_reference_tp"];
      const nextOriginTP = nextOrigin["cipher_of_the_reference_tp"];

      const index = this.compared.operations.findIndex(
        op => op.id_v02 === origin.id_v02
      );
      if (index === -1) return true; // В сравниваемой версии удалена первая операция
      const operation = this.compared.operations[index];

      const nextIndex = this.compared.operations.findIndex(
        op => op.id_v02 === nextOrigin.id_v02
      );
      if (nextIndex === -1) return true; // В сравниваемой версии удалена вторая операция
      const nextOperation = this.compared.operations[nextIndex];

      const type = this.compared.operations[index + 1] === originTP;
      if (operation["cipher_of_the_reference_tp"] !== originTP) return true;
      for (let i = index + 1; i < nextIndex; i++) {
        const TD = this.compared.operations[i]["cipher_of_the_reference_tp"];
        if (TD !== (type ? originTP : nextOriginTP)) return true;
      }
      if (nextOperation["cipher_of_the_reference_tp"] !== nextOriginTP)
        return true;
      return false;
    }
  }
};
</script>

<style lang="scss">
.new {
  background-color: #e8f5e9 !important;
}
.deleted {
  background-color: #ffebee !important;
}
.change {
  color: #ffee58 !important;
}
</style>
