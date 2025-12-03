<template>
  <v-form
    v-model="validation"
    ref="form"
    :disabled="disabled"
    class="w-100 px-2 my-1 form position-relative"
    :class="{ 'shadow-primary': focus }"
  >
    <div class="pa-0 d-flex" @click.stop="$emit('focus')">
      <v-row>
        <v-col cols="6" class="py-0">
          <v-row dense>
            <v-col lg="1">
              <div ref="menu">
                <v-menu
                  v-model="isMoreDataMenuShow"
                  :close-on-content-click="false"
                  top
                  internal-activator
                  :offset-x="true"
                  :open-on-click="false"
                  :attach="$refs.menu"
                >
                  <template v-slot:activator="{ on, attrs }">
                    <v-text-field
                      :value="operation.MoreDataShortString"
                      v-bind="attrs"
                      label="Доп. данные"
                      required
                      readonly
                      v-on="on"
                      class="min-field"
                      @keyup.enter="isMoreDataMenuShow = !isMoreDataMenuShow"
                      @click.right.prevent="
                        isMoreDataMenuShow = !isMoreDataMenuShow
                      "
                      @dblclick="isMoreDataMenuShow = !isMoreDataMenuShow"
                      @focus="$emit('focus')"
                      @blur="$emit('blur')"
                    />
                  </template>
                  <v-card class="overflow-hidden">
                    <div class="d-flex justify-space-between align-top me-2">
                      <v-checkbox
                        v-model="operation.operation_as_needed"
                        value="м/н"
                        label="м/н"
                        class="min-field mx-3 mt-3 py-0"
                      />
                      <v-tooltip right>
                        <template v-slot:activator="{ on: tooltip }">
                          <v-icon color="primary pb-5" v-on="tooltip"
                            >mdi-information</v-icon
                          >
                        </template>
                        <span>Операция, выполняемая по мере необходимости</span>
                      </v-tooltip>
                    </div>
                    <div class="d-flex justify-space-between align-top me-2">
                      <v-checkbox
                        v-model="operation.operations_for_samples"
                        value="обр"
                        label="обр"
                        class="min-field mx-3 my-0 py-0"
                      />
                      <v-tooltip right>
                        <template v-slot:activator="{ on: tooltip }">
                          <v-icon color="primary" class="pb-5" v-on="tooltip"
                            >mdi-information</v-icon
                          >
                        </template>
                        <span>Операция, выполняемая для образцов</span>
                      </v-tooltip>
                    </div>
                    <div class="d-flex justify-space-between align-top me-2">
                      <v-text-field
                        v-model="operation.number_of_worker"
                        placeholder="N"
                        prefix="на "
                        suffix="х."
                        v-mask="'#######'"
                        class="min-field mx-3 my-0 py-0"
                      />
                      <v-tooltip right>
                        <template v-slot:activator="{ on: tooltip }">
                          <v-icon color="primary" class="pb-3" v-on="tooltip"
                            >mdi-information</v-icon
                          >
                        </template>
                        <span>Операция, выполняемая N рабочими</span>
                      </v-tooltip>
                    </div>
                    <div class="d-flex justify-space-between align-top me-2">
                      <v-text-field
                        v-model="
                          operation.operation_with_technological_shutdowns
                        "
                        placeholder="N"
                        suffix=" т.о."
                        v-mask="'#######'"
                        class="min-field mx-3 my-0 py-0"
                      />
                      <v-tooltip right>
                        <template v-slot:activator="{ on: tooltip }">
                          <v-icon color="primary" class="pb-3" v-on="tooltip"
                            >mdi-information</v-icon
                          >
                        </template>
                        <span
                          >Операция, выполняемая с N технологическими остановами
                          <br />
                          на станках с числовым программным управлением</span
                        >
                      </v-tooltip>
                    </div>
                    <div class="d-flex justify-space-between align-top me-2">
                      <v-text-field
                        v-model="operation.operation_for_execution"
                        placeholder="N"
                        prefix="-"
                        v-mask="'#######'"
                        class="min-field mx-3 my-0 py-0"
                        @focusout.native="isMoreDataMenuShow = false"
                      />
                      <v-tooltip right>
                        <template v-slot:activator="{ on: tooltip }">
                          <v-icon color="primary" class="pb-3" v-on="tooltip"
                            >mdi-information</v-icon
                          >
                        </template>
                        <span>Операция, выполняемая для N исполнения</span>
                      </v-tooltip>
                    </div>
                  </v-card>
                </v-menu>
              </div>
            </v-col>
            <v-col lg="1">
              <v-text-field
                v-model.number="operation.end_to_end_operation_number"
                label="№ скв. опер."
                :rules="[
                  rules.required,
                  rules.greaterZero,
                  uniqueValue('end_to_end_operation_number'),
                ]"
                required
                class="min-field"
                @focus="
                  $emit('focus');
                  focusField('end_to_end_operation_number');
                "
                @blur="
                  $emit('blur');
                  unfocusField();
                "
              />
            </v-col>
            <v-col lg="1">
              <v-text-field
                v-model.number="operation.operation_number"
                label="№ опер."
                required
                class="min-field"
                @focus="
                  $emit('focus');
                  focusField('operation_number');
                "
                @blur="
                  $emit('blur');
                  unfocusField();
                "
              />
            </v-col>
            <v-col lg="3">
              <OperationSearch
                v-model="operation.cipher_of_the_operation"
                @focus="
                  $emit('focus');
                  focusField('cipher_of_the_operation');
                "
                @blur="
                  $emit('blur');
                  unfocusField();
                "
              />
            </v-col>
            <v-col lg="3">
              <HardwareSearch
                v-model="operation.hardware_cipher"
                @focus="
                  $emit('focus');
                  focusField('hardware_cipher');
                "
                @blur="
                  $emit('blur');
                  unfocusField();
                "
              />
            </v-col>
            <v-col lg="3">
              <ProfessionSearch
                v-model="operation.cipher_of_the_profession"
                @focus="
                  $emit('focus');
                  focusField('cipher_of_the_profession');
                "
                @blur="
                  $emit('blur');
                  unfocusField();
                "
              />
            </v-col>
          </v-row>
        </v-col>
        <v-col cols="6" class="py-0">
          <v-row dense>
            <v-col lg="1">
              <v-text-field
                v-model="operation.category_of_work"
                label="Р-д"
                :rules="[rules.required, rules.greaterZero]"
                required
                class="min-field"
                v-mask="mask.category_of_work"
                @focus="
                  $emit('focus');
                  focusField('category_of_work');
                "
                @blur="
                  $emit('blur');
                  unfocusField();
                "
              />
            </v-col>
            <v-col lg="1">
              <v-tooltip bottom>
                <template v-slot:activator="{ on, attrs }">
                  <v-text-field
                    v-model="operation.code_of_the_tariff_grid"
                    label="Шифр т.с."
                    :rules="[
                      rules.required,
                      rules.greaterZero,
                      rules.code_of_the_tariff_grid,
                    ]"
                    required
                    class="min-field"
                    v-mask="'00#'"
                    @focus="
                      $emit('focus');
                      focusField('code_of_the_tariff_grid');
                    "
                    @blur="
                      $emit('blur');
                      unfocusField();
                    "
                    v-bind="attrs"
                    v-on="on"
                  />
                </template>
                <span>Шифр т.с.</span>
              </v-tooltip>
            </v-col>
            <v-col lg="1">
              <v-tooltip bottom>
                <template v-slot:activator="{ on, attrs }">
                  <v-text-field
                    v-model="operation.type_of_norms"
                    label="Вид норм"
                    :rules="[rules.required, rules.greaterZero]"
                    required
                    v-mask="mask.type_of_norms"
                    class="min-field"
                    @focus="
                      $emit('focus');
                      focusField('type_of_norms');
                    "
                    @blur="
                      $emit('blur');
                      unfocusField();
                    "
                    v-bind="attrs"
                    v-on="on"
                  />
                </template>
                <span>Вид норм</span>
              </v-tooltip>
            </v-col>
            <v-col lg="1">
              <v-tooltip bottom>
                <template v-slot:activator="{ on, attrs }">
                  <v-text-field
                    v-model="operation.unit_of_the_rationong"
                    label="Ед.норм."
                    :rules="[rules.required, rules.greaterZero]"
                    required
                    class="min-field"
                    v-mask="'#'"
                    @focus="
                      $emit('focus');
                      focusField('unit_of_the_rationong');
                    "
                    @blur="
                      $emit('blur');
                      unfocusField();
                    "
                    v-bind="attrs"
                    v-on="on"
                  />
                </template>
                <span>Ед.норм.</span>
              </v-tooltip>
            </v-col>
            <v-col lg="2">
              <v-tooltip bottom>
                <template v-slot:activator="{ on, attrs }">
                  <v-text-field
                    v-model="operation.time_rate_is_paid"
                    label="Время опл."
                    :rules="[rules.required, rules.time_rate_is_paid]"
                    v-mask="mask.time"
                    required
                    class="min-field"
                    @focus="
                      $emit('focus');
                      focusField('time_rate_is_paid');
                    "
                    @blur="
                      $emit('blur');
                      unfocusField();
                    "
                  />
                </template>
                <span>Время опл.</span>
              </v-tooltip>
            </v-col>
            <v-col lg="2">
              <v-tooltip bottom>
                <template v-slot:activator="{ on, attrs }">
                  <v-text-field
                    v-model="operation.norm_of_cycle_time"
                    label="ТЦ"
                    required
                    :rules="[rules.time_rate_is_paid]"
                    class="min-field"
                    v-mask="mask.time"
                    @focus="
                      $emit('focus');
                      focusField('norm_of_cycle_time');
                    "
                    @blur="
                      $emit('blur');
                      unfocusField();
                    "
                    v-bind="attrs"
                    v-on="on"
                  />
                </template>
                <span>Время цикла</span>
              </v-tooltip>
            </v-col>
            <v-col lg="2">
              <v-tooltip bottom>
                <template v-slot:activator="{ on, attrs }">
                  <v-text-field
                    v-model="operation.launch_ratio"
                    label="К-т зап."
                    required
                    class="min-field"
                    @focus="
                      $emit('focus');
                      focusField('launch_ratio');
                    "
                    @blur="
                      $emit('blur');
                      unfocusField();
                    "
                    v-bind="attrs"
                    v-on="on"
                  />
                </template>
                <span>Коэффициент запуска</span>
              </v-tooltip>
            </v-col>
            <v-col lg="2">
              <v-text-field
                v-model="operation.number_notification_sgt"
                label="№ извещ."
                required
                class="min-field"
                @focus="
                  $emit('focus');
                  focusField('number_notification_sgt');
                "
                @blur="
                  $emit('blur');
                  unfocusField();
                "
              />
            </v-col>
          </v-row>
        </v-col>
      </v-row>
      <!-- Кнопки  -->
      <div class="pl-2 d-flex align-center" style="flex: 0 0 100px">
        <v-tooltip bottom>
          <template v-slot:activator="{ on, attrs }">
            <v-btn
              class="operation-button mx-1"
              fab
              small
              text
              v-bind="attrs"
              v-on="on"
              @click.stop="$emit('added-new-operation')"
              :disabled="disabled"
              @focus="$emit('focus')"
              @blur="$emit('blur')"
            >
              <v-icon small>mdi-table-row-plus-after</v-icon>
            </v-btn>
          </template>
          <span>Добавить операцию после данной</span>
        </v-tooltip>
        <v-tooltip bottom>
          <template v-slot:activator="{ on, attrs }">
            <v-btn
              class="operation-button mx-1"
              fab
              small
              text
              v-bind="attrs"
              v-on="on"
              @click.stop="$emit('copied-operation')"
              :disabled="disabled"
              @focus="$emit('focus')"
              @blur="$emit('blur')"
            >
              <v-icon small>mdi-content-copy</v-icon>
            </v-btn>
          </template>
          <span>Копировать данную операцию</span>
        </v-tooltip>
        <v-tooltip bottom>
          <template v-slot:activator="{ on, attrs }">
            <v-btn
              class="operation-button operation-button--red mx-1"
              fab
              small
              text
              v-bind="attrs"
              v-on="on"
              @click.stop="$emit('deleted-operation')"
              :disabled="disabled"
              @focus="$emit('focus')"
              @blur="$emit('blur')"
            >
              <v-icon small>mdi-delete</v-icon>
            </v-btn>
          </template>
          <span>Удалить данную операцию</span>
        </v-tooltip>
      </div>
    </div>
  </v-form>
</template>

<script>
import { mapState, mapMutations } from "vuex";

import OperationSearch from "./OperationSearch";
import HardwareSearch from "./HardwareSearch";
import ProfessionSearch from "./ProfessionSearch";

export default {
  name: "OperationFormItem",
  components: {
    OperationSearch,
    HardwareSearch,
    ProfessionSearch,
  },
  props: {
    operation: Object,
    focus: Boolean,
    disabled: Boolean,
  },
  data: () => ({
    validation: false,
    isMoreDataMenuShow: false,
    rules: {
      time_rate_is_paid: (value) =>
        /^\d{1,3}(?:(\.|,)\d{1,2})?(\s{1}ч)?$/i.test(value) ||
        "Неверное значение",
      code_of_the_tariff_grid: (value) =>
        /^00[1-7]$/.test(value) || "Неверное значение",
      onlyNumbers: (value) => /^(\d+)$/.test(value) || "",
      required: (value) => !!value || "Введите значение",
      greaterZero: (value) => value > 0 || "Введите значение",
    },
    mask: {
      type_of_norms: [/([1-7])/],
      category_of_work: [/([1-8])/],
      time: [
        /[1-9]/,
        /[\d|\s|(.)]/,
        /[\d|\s|(.)|ч]/,
        /[\d|\s|(.)|ч]/,
        /[\d|\s|(.)|ч]/,
        /[\s|(.)|ч]/,
        /[ч]/,
      ],
    },
  }),
  computed: {
    ...mapState("card", {
      operations: (state) => state.operations,
    }),
  },
  mounted() {
    setTimeout(() => {
      this.$refs.form.validate();
    }, 100);
  },
  methods: {
    ...mapMutations("operation/focus", ["focusField", "unfocusField"]),
    uniqueValue(field) {
      return (value) => {
        return (
          this.operations.filter((operation) => {
            return String(operation[field]) === String(value);
          }).length === 1 || "Значение должно быть уникальным"
        );
      };
    },
    validate() {
      this.$refs.form.validate();
    },
    resetValidation() {
      this.$refs.form.resetValidation();
    },
  },
};
</script>
