<template>
    <div v-if="input_type == 'number'">
        <div class="input_oneline">
            <label :for="input_id"><slot /></label>
            <input
                :id="input_id"
                type="number"
                :min="minimum"
                :max="maximum"
                :value="modelValue"
                @input="$emit('update:modelValue', $event.target.value)"
            />
        </div>
    </div>

    <div v-if="input_type == 'text'">
        <div class="input_oneline">
            <label :for="input_id"><slot /></label>
            <input
                :id="input_id"
                type="text"
                :value="modelValue"
                @input="$emit('update:modelValue', $event.target.value)"
            />
        </div>
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

    <div v-if="input_type == 'year'" class="input_oneline">
        <label :for="input_id"><slot /></label>
        <div class="input_ad_year">
            AD
            <input
                :id="input_id"
                type="text"
                :value="modelValue"
                @input="$emit('update:modelValue', $event.target.value)"
            />
        </div>
    </div>

    <div v-if="input_type == 'textarea'" class="input_area">
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
            @input="$emit('update:modelValue', $event.target.value)"
        >
            <option value="">Select</option>
            <option
                v-for="choice in choices"
                :value="choice.id"
                :key="choice.id"
            >
                {{ choice.name }}
            </option>
        </select>
    </div>

    <div v-if="input_type == 'legal_choice'" class="input_oneline">
        <label :for="input_id"><slot /></label>
        <select
            :id="input_id"
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
            class="dropdownmenu"
        >
            <option value=""></option>
            <option
                v-for="choice in choices"
                :value="choice.id"
                :key="choice.id"
            >
                {{ choice.name }}: {{ choice.description }}
            </option>
        </select>
    </div>

    <div v-if="input_type == 'multi_choice'" class="input_oneline">
        <label :for="input_id"><slot /></label>
        <button
            :id="input_id"
            @click.prevent="dropdown = !dropdown"
            class="dropdownbutton"
        >
            <span>Select</span>
            <span>⌄</span>
        </button>
        <div>
            <div class="topborder">
                <span
                    v-for="value in modelValue"
                    :key="value.id"
                    class="choiceelement"
                >
                    <button
                        @click.prevent="removechoice(value)"
                        class="removebutton"
                    >
                        ✕
                    </button>

                    {{ value.name }}
                </span>
            </div>
            <div
                v-if="dropdown"
                class="dropdown-content"
                @mouseleave="dropdown = false"
            >
                <div v-if="choices.length > 20">
                    <label :for="search">Search:</label>
                    <input type="text" v-model="search" />
                </div>
                <div class="scrollwindow">
                    <div v-for="choice in search_choices" :key="choice.id">
                        <input
                            type="checkbox"
                            :id="choice.id"
                            :value="choice"
                            v-model="checked"
                            @change="$emit('update:modelValue', checked)"
                        />
                        <label>{{ choice.name }}</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div v-if="input_type == 'party_choice'" class="input_oneline">
        <label :for="input_id"><slot /></label>
        <p style="border-style: solid">
            <b>Selected:</b>
            <span v-for="value in modelValue" :key="value.id"
                >{{ value.name }},</span
            >
        </p>
        <div v-if="choices.length > 9">
            <label :for="search">Search:</label>
            <input type="text" v-model="search" />
        </div>

        <div class="scrollwindow">
            <div v-for="choice in search_choices" :key="choice.id">
                <input
                    type="checkbox"
                    :id="choice.id"
                    :value="choice"
                    v-model="checked"
                    @change="$emit('update:modelValue', checked)"
                />
                <label>{{ choice.name }}</label>
            </div>
        </div>
    </div>

    <div v-if="input_type == 'multi_choice_scripts'" class="input_oneline">
        <label :for="input_id"><slot /></label>
        <button @click.prevent="dropdown = !dropdown" class="dropdownbutton">
            <span>Select</span>
            <span>⌄</span>
        </button>
        <div>
            <p class="topborder">
                <span
                    v-for="value in modelValue"
                    :key="value.id"
                    class="choiceelement"
                >
                    <button
                        @click.prevent="removechoice(value)"
                        class="removebutton"
                    >
                        ✕
                    </button>
                    {{ value.name }}
                </span>
            </p>
            <div
                v-if="dropdown"
                class="dropdown-content"
                @mouseleave="dropdown = false"
            >
                <h3>Greek Scripts:</h3>
                <div v-for="choice in search_choices" :key="choice.id">
                    <div v-if="choice.language_id == '1'">
                        <input
                            type="checkbox"
                            :id="choice.id"
                            :value="choice"
                            v-model="checked"
                            @change="$emit('update:modelValue', checked)"
                        />
                        <label>{{ choice.name }}</label>
                    </div>
                </div>

                <h3>Latin Scripts:</h3>
                <div v-for="choice in search_choices" :key="choice.id">
                    <div v-if="choice.language_id == '2'">
                        <input
                            type="checkbox"
                            :id="choice.id"
                            :value="choice"
                            v-model="checked"
                            @change="$emit('update:modelValue', checked)"
                        />
                        <label>{{ choice.name }}</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div v-if="input_type == 'document_choice'" class="input_oneline">
        <label :for="input_id"><slot /></label>
        <button @click.prevent="dropdown = !dropdown" class="dropdownbutton">
            <span>Select</span>
            <span>⌄</span>
        </button>
        <div>
            <p class="topborder">
                <span
                    v-for="value in modelValue"
                    :key="value.id"
                    class="choiceelement"
                >
                    <button
                        @click.prevent="removechoice(value)"
                        class="removebutton"
                    >
                        ✕
                    </button>
                    {{ value.standard_name }}</span
                >
            </p>
            <div
                v-if="dropdown"
                class="dropdown-content"
                @mouseleave="dropdown = false"
            >
                <div v-if="choices.length > 20">
                    <label :for="search">Search:</label>
                    <input type="text" v-model="search" />
                </div>
                <div class="scrollwindow">
                    <div v-for="choice in search_choices_doc" :key="choice.id">
                        <input
                            type="checkbox"
                            :id="choice.id"
                            :value="choice"
                            v-model="checked"
                            @change="$emit('update:modelValue', checked)"
                        />
                        <label>
                            {{ choice.standard_name }} (Tr.ID:
                            {{ choice.trismegistos_id }})</label
                        >
                    </div>
                </div>
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
        <p style="border-style: solid">
            <b>Selected:</b>
            <span v-for="value in modelValue" :key="value.id">
                {{ value.standard_name }},
            </span>
        </p>

        <div v-if="choices.length > 9">
            <label :for="search">Search:</label>
            <input type="text" v-model="search" />
        </div>

        <div class="scrollwindow">
            <div v-for="choice in search_choices_doc" :key="choice.id">
                <input
                    type="checkbox"
                    :id="choice.id"
                    :value="choice"
                    v-model="checked"
                    @change="$emit('update:modelValue', checked)"
                />
                <label>
                    {{ choice.standard_name }} (Tr.ID:
                    {{ choice.trismegistos_id }})
                    <span
                        v-if="
                            choice.modern_collection_id &&
                            choice.modern_collection_id != collection_id
                        "
                    >
                        <b>
                            Assigned to
                            {{
                                collections.find(
                                    (x) => x.id === choice.modern_collection_id
                                ).name
                            }}</b
                        >
                    </span>
                </label>
            </div>
        </div>
    </div>

    <div v-if="input_type == 'multi_choice_edit_delete'" class="input_oneline">
        <label :for="input_id"><slot /></label>
        <p style="border-style: solid">
            <b>Selected:</b
            ><span v-for="value in modelValue" :key="value.id"
                >{{ value.name }},</span
            >
        </p>
        <div v-if="choices.length > 9">
            <label :for="search">Search:</label>
            <input type="text" v-model="search" />
        </div>
        <div v-for="choice in search_choices" :key="choice.id">
            <input
                type="checkbox"
                :id="choice.id"
                :value="choice"
                v-model="checked"
                @change="$emit('update:modelValue', checked)"
            />
            <label>{{ choice.name }}</label>
            <button @click.prevent="del_choice(choice.id)">Delete</button>
            <button @click.prevent="edit_choice(choice.id)">Edit</button>
        </div>
    </div>

    <div v-if="input_type == 'bifolia'" class="input_oneline">
        <label :for="input_id"><slot /></label>
        <div v-for="n in range(1, num)" :key="n">
            <p>
                <span v-if="num > 1">{{ n }}:</span
                ><input
                    type="number"
                    min="1"
                    required
                    :value="bifolia[n - 1] ? bifolia[n - 1] : 1"
                    @input="bifolia_input(n, $event.target.value)"
                />
            </p>
        </div>
        {{ JSON.stringify(bifolia) }}
    </div>
</template>

<script setup>
import { onMounted, watch } from "vue";
import { ref, computed } from "vue";

let search = ref("");
let dropdown = ref(false);

const props = defineProps({
    modelValue: [String, Number, Array, Boolean],
    input_id: String,
    input_type: String,
    choices: Array,
    collection_id: Number,
    collections: Array,
    num: Number,
    minimum: Number,
    maximum: Number,
    name: { type: String, default: "name" },
});

let checked = ref(props.modelValue);

let bifolia = ref(props.modelValue);

let testval = ref();

const emit = defineEmits(["update:modelValue"]);

function del_choice(id) {
    if (confirm("Do you really want to delete record #" + id + "?")) {
    }
}

function removechoice(choice) {
    this.checked = this.checked.filter((obj) => {
        return obj.id !== choice.id;
    });

    emit("update:modelValue", checked);
}

function edit_choice(id) {
    alert("Edit #" + id + ".");
}

function range(start, end) {
    var foo = [];
    for (var i = start; i <= end; i++) {
        foo.push(i);
    }
    return foo;
}

function bifolia_input(n, value) {
    bifolia.value[n - 1] = value;
    emit("update:modelValue", bifolia.value);
}

const search_choices = computed(() => {
    return search.value != ""
        ? props.choices.filter(function (el) {
              return el.name != null ? el.name.includes(search.value) : null;
          })
        : props.choices;
});

const search_choices_doc = computed(() => {
    return search.value != ""
        ? props.choices.filter(function (el) {
              return el.standard_name != null
                  ? el.standard_name.includes(search.value)
                  : null || el.trismegistos_id != null
                  ? el.trismegistos_id.toString().includes(search.value)
                  : null;
          })
        : props.choices;
});
</script>

<style>
select {
    background-color: #eee;
    min-width: 10em;
    width: fit-content;
    height: fit-content;
    border-radius: 10px;
    margin-top: 10px;
    padding: 5px 10px;
    font-size: 16px;
    border: none;
    cursor: pointer;
}

.dropdown-content {
    display: block;
    position: absolute;
    background-color: #f9f9f9;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
    z-index: 1;
    padding: 20px;
    border-radius: 10px;
}

.choiceelement {
    display: inline-block;
    background-color: #fff; /* Changing background color */
    border-radius: 10px; /* Making border radius */
    width: auto; /* Making auto-sizable width */
    height: auto; /* Making auto-sizable height */
    padding: 2px 10px 2px 10px; /* Making space around letters */
    margin: 2px;
    font-size: 12px; /* Changing font size */
}

.topborder {
    margin-top: 10px;
}

.scrollwindow {
    max-height: 180px;
    overflow: auto;
}

.input_oneline {
    display: flex;
    flex-direction: column;
    height: 100%;
    width: 100%;
}

.input_oneline label {
    padding-top: 15px;
    padding-right: 15px;
    font-weight: bold;
}
.input_oneline input {
    padding: 8px;
    margin: 4px;
    border-radius: 5px;
    font-size: 16px;
}

.input_area {
    display: flex;
    flex-direction: column;
    height: 100%;
    width: 100%;
}

.input_area textarea {
    resize: none;
    box-sizing: border-box;
    height: 100%;
    border-radius: 10px;
    padding: 8px;
    font-size: 16px;
}

.input_ad_year {
    display: flex;
    flex-direction: row;
    align-items: center;
}

h3 {
    padding-top: 1em;
}

.removebutton {
    all: unset;
    cursor: pointer;
}
</style>
