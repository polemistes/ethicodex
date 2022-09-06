<template>
    <div v-if="input_type == 'number' || input_type == 'text'" class="input_oneline">
      <label :for="input_id"><slot /></label>
      <input 
          :id="input_id" 
          :type="input_type"
          :value="modelValue"
          @input="$emit('update:modelValue', $event.target.value)"
        />
    </div>

    <div v-if="input_type == 'password'" class="input_oneline">
      <label :for="input_id"><slot /></label>
      <input 
          :id="input_id" 
          :type="input_type"
          :value="modelValue"
          @input="$emit('update:modelValue', $event.target.value)"
          required
        />
    </div>

    <div v-if="input_type == 'bool'" class="input_oneline">
      <input  
          :id="input_id" 
          type="checkbox"
          :value="modelValue"
          @change="$emit('update:modelValue', $event.target.value)"
          required
        />
        <label :for="input_id"><slot /></label>
    </div>

    <div v-if="input_type == 'year'" class="input_year">
      <label :for="input_id"><slot /></label>
      AD <input 
          :id="input_id" 
          type="text"
          :value="modelValue"
          @input="$emit('update:modelValue', $event.target.value)"
        />
    </div>

    <div v-if="input_type == 'textarea'" class="input_oneline">
      <label :for="input_id"><slot /></label>
      <textarea 
          :id="input_id" 
          :value="modelValue"
          @input="$emit('update:modelValue', $event.target.value)"
        />
    </div>

    <div v-if="input_type == 'single_choice'" class="input_oneline">
      <label :for="input_id"><slot /></label>
      <select 
          :id="input_id" 
          :value="modelValue"
          @input="$emit('update:modelValue', $event.target.value)">
          <option value=""></option>
          <option 
              v-for="choice in choices" 
              :value="choice.id" 
              :key="choice.id">
              {{ choice.name }}
          </option>
      </select>
    </div>

    <div v-if="input_type == 'multi_choice'" class="input_oneline">
      <label :for="input_id"><slot /></label>
      <p style="border-style: solid;"><b>Selected:</b> <span v-for="value in modelValue" :key="value.id">{{ value.name }},</span></p>
      <div v-if="choices.length > 9">
        <label :for="search">Search:</label>
        <input type="text" v-model="search">
      </div>      <div class="scrollwindow">
      <div v-for="choice in search_choices" :key="choice.id">
        <input type="checkbox"
              :id="choice.id"  
              :value="choice"
              v-model="modelValue"
              @change="$emit('update:modelValue', modelValue)">
        <label>{{ choice.name }}</label>
      </div>
      </div>    
    </div>

    <div v-if="input_type == 'multi_choice_scripts'" class="input_oneline">
      <label :for="input_id"><slot /></label>
      <p style="border-style: solid;">
        <b>Selected:</b> 
        <span v-for="value in modelValue" :key="value.id">{{ value.name }}
          ,
        </span>
      </p>
      <h3>Greek Scripts:</h3>
      <div v-for="choice in search_choices" :key="choice.id">
        <div v-if="choice.language_id == '1'">
          <input type="checkbox"
              :id="choice.id"  
              :value="choice"
              v-model="modelValue"
              @change="$emit('update:modelValue', modelValue)"
          >
          <label>{{ choice.name }}</label>
        </div>
      </div>

      <h3>Latin Scripts:</h3>
      <div v-for="choice in search_choices" :key="choice.id">
        <div v-if="choice.language_id == '2'">
          <input type="checkbox"
              :id="choice.id"  
              :value="choice"
              v-model="modelValue"
              @change="$emit('update:modelValue', modelValue)"
          >
          <label>{{ choice.name }}</label>
        </div>
      </div>
    </div>

    <div v-if="input_type == 'document_choice'" class="input_oneline">
      <label :for="input_id"><slot /></label>
      <p style="border-style: solid;"><b>Selected:</b> <span v-for="value in modelValue" :key="value.id">{{ value.standard_name }},</span></p>
      <div v-if="choices.length > 9">
        <label :for="search">Search:</label>
        <input type="text" v-model="search">
      </div>
      <div class="scrollwindow">
      <div v-for="choice in search_choices_doc" :key="choice.id">
        <input type="checkbox"
              :id="choice.id"  
              :value="choice"
              v-model="modelValue"
              @change="$emit('update:modelValue', modelValue)">
        <label> {{ choice.standard_name }} (Tr.ID: {{ choice.trismegistos_id }})</label>
      </div>
      </div>    
    </div>

<!--
    <div v-if="input_type == 'document_choice_taken'" class="input_oneline">
      
      <label :for="input_id"><slot /></label>
      <p style="border-style: solid;">
        <b>Selected:</b> 
        <span v-for="value in modelValue" :key="value.id">
          {{ value.standard_name }},
        </span>
      </p>
      
      <div v-if="choices.length > 9">
        <label :for="search">Search:</label>
        <input type="text" v-model="search">
      </div>
      
      <div class="scrollwindow">
        <div v-for="choice in search_choices_doc" :key="choice.id">
          <input type="checkbox"
              :id="choice.id"  
              :value="choice"
              v-model="modelValue"
              @change="$emit('update:modelValue', modelValue)">
          <label> 
            {{ choice.standard_name }} (Tr.ID: {{ choice.trismegistos_id }})
            <span v-if="choice.modern_collection_id && choice.modern_collection_id != collection_id">
              <b> Assigned to {{ collections.find(x => x.id === choice.modern_collection_id).name }}</b>
            </span>
          </label>
        </div>
      </div>    
    </div>
-->

    <div v-if="input_type == 'document_choice_modern'" class="input_oneline">
 
      <label :for="input_id"><slot /></label>
      <p style="border-style: solid;">
        <b>Selected:</b> 
        <span v-for="value in modelValue" :key="value.id">
          {{ value.standard_name }},
        </span>
      </p>
      
      <div v-if="choices.length > 9">
        <label :for="search">Search:</label>
        <input type="text" v-model="search">
      </div>
      
      <div class="scrollwindow">
        <div v-for="choice in search_choices_doc" :key="choice.id">
          <input type="checkbox"
              :id="choice.id"  
              :value="choice"
              v-model="modelValue"
              @change="$emit('update:modelValue', modelValue)">
          <label> 
            {{ choice.standard_name }} (Tr.ID: {{ choice.trismegistos_id }})
            <span v-if="choice.modern_collection_id && choice.modern_collection_id != collection_id">
              <b> Assigned to {{ collections.find(x => x.id === choice.modern_collection_id).name }}</b>
            </span>
          </label>
        </div>
      </div>    
    </div>

    <div v-if="input_type == 'multi_choice_edit_delete'" class="input_oneline">
      <label :for="input_id"><slot /></label>
      <p style="border-style: solid;"><b>Selected:</b><span v-for="value in modelValue" :key="value.id">{{ value.name }},</span></p>
      <div v-if="choices.length > 9">
        <label :for="search">Search:</label>
        <input type="text" v-model="search">
      </div>
      <div v-for="choice in search_choices" :key="choice.id">
        <input type="checkbox"
              :id="choice.id"  
              :value="choice"
              v-model="modelValue"
              @change="$emit('update:modelValue', modelValue)">
        <label>{{ choice.name }}</label>
        <button @click.prevent="del_choice(choice.id)">Delete</button>
         <button @click.prevent="edit_choice(choice.id)">Edit</button>
      </div>    
    </div>

    <div v-if="input_type == 'bifolia'" class="input_oneline">
      <label :for="input_id"><slot /></label>
      <div v-for="n in range(1, num)" :key="n" ><p><span v-if="num > 1">{{ n }}:</span><input type="number" min="1" required :value="bifolia[n-1] ? bifolia[n-1] : 1" @input="bifolia_input(n, $event.target.value)"></p></div>    
      {{ JSON.stringify(bifolia) }} 
    </div>

</template>

<script setup>
import { onMounted, watch } from 'vue';
import { ref, computed } from 'vue'

let search = ref("");

const props = defineProps({
  modelValue: [String, Number, Array, Boolean],
  input_id: String, 
  input_type: String,
  choices: Array,
  collection_id: Number,
  collections: Array,
  num: Number,
  name: {type: String, default: "name"}
})

let bifolia = ref(props.modelValue);

const emit = defineEmits(['update:modelValue'])
//const ch = ref(props.choices);

function del_choice(id) {
  if(confirm("Do you really want to delete record #" + id + "?")) {
    
  };
}

function edit_choice(id) {
  alert("Edit #" + id + ".")
}

function range(start, end) {
    var foo = [];
    for (var i = start; i <= end; i++) {
        foo.push(i);
    }
    return foo;
}

function bifolia_input(n, value) {
  bifolia.value[n-1] = value
  emit('update:modelValue', bifolia.value)
}

const search_choices = computed(() => {
  return search.value != "" ? props.choices.filter(function (el) {
      return (el.name != null) ? el.name.includes(search.value) : null }) : props.choices
})

const search_choices_doc = computed(() => {
  return search.value != "" ? props.choices.filter(function (el) {
      return (el.standard_name != null) ? el.standard_name.includes(search.value) : null || 
            (el.trismegistos_id != null) ? el.trismegistos_id.toString().includes(search.value) : null}) : props.choices
})

</script>

<style>
label {
  padding-top: 15px;
  padding-right: 15px;
  font-weight: bold;
}

.scrollwindow {
  max-height: 180px;
  overflow: auto;
}

.input_oneline {
  display: flex;
  flex-direction: column;
}

.input_year {
  display: flex;
  flex-direction: column;
}
h3 {
  padding-top: 1em;
}
</style>