<template>
  <v-form disabled class="w-100 px-2 position-relative">
    <div class="pa-0 d-flex">
      <v-row>
        <v-col cols="6" class="py-0">
          <v-row dense>
            <v-col lg="1">
              <v-text-field
                :value="operation.MoreDataShortString"
                label="Доп. данные"
                class="min-field"
              />
            </v-col>
            <v-col lg="1">
              <v-text-field
                v-model.number="operation.end_to_end_operation_number"
                label="№ скв. опер."
                class="min-field"
              />
            </v-col>
            <v-col lg="1">
              <v-text-field
                v-model.number="operation.operation_number"
                label="№ опер."
                class="min-field"
              />
            </v-col>
            <v-col lg="3">
              <v-text-field
                v-model="operation.cipher_of_the_operation.p018"
                label="Операция"
                class="min-field"
                persistent-hint
                :hint="operation.cipher_of_the_operation.p014"
              />
            </v-col>
            <v-col lg="3">
              <v-text-field
                v-model="operation.hardware_cipher.p501"
                label="Оборудование"
                class="min-field"
                persistent-hint
                :hint="operation.hardware_cipher.p451"
              />
            </v-col>
            <v-col lg="3">
              <v-text-field
                v-model="operation.cipher_of_the_profession.profcode70"
                label="Профессия"
                class="min-field"
                persistent-hint
                :hint="operation.cipher_of_the_profession.snm_as_scaption"
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
                class="min-field"
              />
            </v-col>
            <v-col lg="1">
              <v-text-field
                v-model="operation.code_of_the_tariff_grid"
                label="Шифр т.с."
                class="min-field"
                v-mask="'00#'"
              />
            </v-col>
            <v-col lg="1">
              <v-text-field
                v-model="operation.type_of_norms"
                label="Вид норм"
                class="min-field"
              />
            </v-col>
            <v-col lg="1">
              <v-text-field
                v-model="operation.unit_of_the_rationong"
                label="Ед. норм."
                class="min-field"
                v-mask="'#'"
              />
            </v-col>
            <v-col lg="2">
              <v-text-field
                v-model="operation.time_rate_is_paid"
                label="Время"
                class="min-field"
              />
            </v-col>
            <v-col lg="2">
              <v-text-field
                v-model="operation.norm_of_cycle_time"
                label="ТЦ"
                class="min-field"
              />
            </v-col>
            <v-col lg="2">
              <v-text-field
                v-model="operation.launch_ratio"
                label="К-т зап."
                class="min-field"
              />
            </v-col>
            <v-col lg="2">
              <v-text-field
                v-model="operation.number_notification_sgt"
                label="№ извещ."
                class="min-field"
              />
            </v-col>
            <!-- расчётные поля -->
            <v-col lg="2">
              <v-text-field label="Расценка (руб)" class="min-field" />
            </v-col>
            <v-col lg="2">
              <v-text-field label="Время опл. с Кзо" class="min-field" />
            </v-col>
            <v-col lg="2">
              <v-text-field label="Время цикла с Кзо" class="min-field" />
            </v-col>
            <v-col lg="2">
              <v-text-field label="Расценка с Кзо" class="min-field" />
            </v-col>
          </v-row>
        </v-col>
      </v-row>
    </div>
  </v-form>
</template>

<script>
export default {
  name: "OperationFormItem",
  props: {
    operation: Object
  }
};
</script>
