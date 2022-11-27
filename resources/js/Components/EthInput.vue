<template>
    <div v-if="input_type == 'number'">
        <div class="inputfield">
            <label :for="input_id"><slot /></label>
            <input
                :id="input_id"
                type="number"
                :min="minimum ? minimum : 0"
                :max="maximum"
                :value="modelValue"
                @input="$emit('update:modelValue', $event.target.value)"
            />
        </div>
    </div>

    <div v-if="input_type == 'text'">
        <div class="inputfield">
            <label :for="input_id"><slot /></label>
            <input
                :id="input_id"
                type="text"
                :value="modelValue"
                @input="$emit('update:modelValue', $event.target.value)"
            />
        </div>
    </div>

    <div v-if="input_type == 'textevent'">
        <div class="inputfield">
            <label :for="input_id"><slot /></label>
            <input
                :id="input_id"
                type="text"
                :value="modelValue"
                @input="
                    $emit('update:modelValue', $event.target.value);
                    $emit('keyPressed');
                "
            />
        </div>
    </div>

    <div v-if="input_type == 'numberevent'">
        <div class="inputfield">
            <label :for="input_id"><slot /></label>
            <input
                :id="input_id"
                type="number"
                :min="minimum ? minimum : 0"
                :max="maximum"
                :value="modelValue"
                @input="
                    $emit('update:modelValue', $event.target.value);
                    $emit('keyPressed');
                "
                style="width: 5em"
            />
        </div>
    </div>

    <div v-if="input_type == 'password'" class="inputfield">
        <label :for="input_id"><slot /></label>
        <input
            :id="input_id"
            :type="input_type"
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
            required
        />
    </div>

    <div v-if="input_type == 'bool'" class="inputfield">
        <div
            style="
                display: flex;
                flex-direction: row;
                align-items: center;
                padding: 10px;
            "
        >
            <label :for="input_id"><slot /></label>

            <input
                :id="input_id"
                type="checkbox"
                v-model="checked"
                @change="$emit('update:modelValue', checked)"
            />
        </div>
    </div>

    <div v-if="input_type == 'boolevent'" class="inputfield">
        <div
            style="
                display: flex;
                flex-direction: row;
                align-items: center;
                padding: 10px;
            "
        >
            <label :for="input_id"><slot /></label>

            <input
                :id="input_id"
                type="checkbox"
                v-model="checked"
                @change="
                    $emit('update:modelValue', checked);
                    $emit('newChange');
                "
            />
        </div>
    </div>

    <div v-if="input_type == 'year'" class="inputfield">
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

    <div v-if="input_type == 'textarea'" class="inputfield">
        <label :for="input_id"><slot /></label>
        <textarea
            :id="input_id"
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
        />
    </div>

    <div v-if="input_type == 'single_choice'" class="inputfield">
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

    <div v-if="input_type == 'single_choice_event'" class="inputfield">
        <label :for="input_id"><slot /></label>
        <select
            :id="input_id"
            :value="modelValue"
            @input="
                $emit('update:modelValue', $event.target.value);
                $emit('newChange');
            "
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

    <div v-if="input_type == 'legal_choice'" class="inputfield">
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

    <div v-if="input_type == 'multi_choice'" class="inputfield">
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
            <div class="choicelist">
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
                <div class="dropdown-scrollwindow">
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

    <div v-if="input_type == 'multi_choice_event'" class="inputfield">
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
            <div class="choicelist">
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
                <div class="dropdown-scrollwindow">
                    <div v-for="choice in search_choices" :key="choice.id">
                        <input
                            type="checkbox"
                            :id="choice.id"
                            :value="choice"
                            v-model="checked"
                            @change="
                                $emit('update:modelValue', checked);
                                $emit('newChange');
                            "
                        />
                        <label>{{ choice.name }}</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div v-if="input_type == 'multi_choice_legal_event'" class="inputfield">
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
            <div class="choicelist">
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
                <div class="dropdown-scrollwindow">
                    <div v-for="choice in search_choices" :key="choice.id">
                        <input
                            type="checkbox"
                            :id="choice.id"
                            :value="choice"
                            v-model="checked"
                            @change="
                                $emit('update:modelValue', checked);
                                $emit('newChange');
                            "
                        />
                        <label
                            >{{ choice.name }}: {{ choice.description }}</label
                        >
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div v-if="input_type == 'multi_choice_search'" class="inputfield">
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
            <div
                v-if="dropdown"
                class="dropdown-content"
                @mouseleave="dropdown = false"
            >
                <div>
                    <input
                        type="checkbox"
                        id="show_publication"
                        v-model="publication_checked"
                        @change="
                            $emit('update:publication', publication_checked);
                            $emit('newChange');
                        "
                    />
                    <label>Publication</label>
                </div>

                <div>
                    <input
                        type="checkbox"
                        id="show_content"
                        v-model="content_checked"
                        @change="
                            $emit('update:content', content_checked);
                            $emit('newChange');
                        "
                    />
                    <label>Content</label>
                </div>

                <div>
                    <input
                        type="checkbox"
                        id="show_dating"
                        v-model="dating_checked"
                        @change="
                            $emit('update:dating', dating_checked);
                            $emit('newChange');
                        "
                    />
                    <label>Date</label>
                </div>

                <div>
                    <input
                        type="checkbox"
                        id="show_materiality"
                        v-model="materiality_checked"
                        @change="
                            $emit('update:materiality', materiality_checked);
                            $emit('newChange');
                        "
                    />
                    <label>Materiality</label>
                </div>

                <div>
                    <input
                        type="checkbox"
                        id="show_measurement"
                        v-model="measurement_checked"
                        @change="
                            $emit('update:measurement', measurement_checked);
                            $emit('newChange');
                        "
                    />
                    <label>Measurements</label>
                </div>

                <div>
                    <input
                        type="checkbox"
                        id="show_palaeography"
                        v-model="palaeography_checked"
                        @change="
                            $emit('update:palaeography', palaeography_checked);
                            $emit('newChange');
                        "
                    />
                    <label>Palaeography</label>
                </div>

                <div>
                    <input
                        type="checkbox"
                        id="show_consanal"
                        v-model="consanal_checked"
                        @change="
                            $emit('update:consanal', consanal_checked);
                            $emit('newChange');
                        "
                    />
                    <label>Conservation and Analysis</label>
                </div>

                <div>
                    <input
                        type="checkbox"
                        id="show_provenance"
                        v-model="provenance_checked"
                        @change="
                            $emit('update:provenance', provenance_checked);
                            $emit('newChange');
                        "
                    />
                    <label>Provenance</label>
                </div>
            </div>
        </div>
    </div>

    <div v-if="input_type == 'party_choice'" class="inputfield">
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

        <div class="dropdown-scrollwindow">
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

    <div v-if="input_type == 'multi_choice_scripts'" class="inputfield">
        <label :for="input_id"><slot /></label>
        <button @click.prevent="dropdown = !dropdown" class="dropdownbutton">
            <span>Select</span>
            <span>⌄</span>
        </button>
        <div>
            <div class="choicelist">
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

    <div v-if="input_type == 'document_choice'" class="inputfield">
        <label :for="input_id"><slot /></label>
        <button @click.prevent="dropdown = !dropdown" class="dropdownbutton">
            <span>Select</span>
            <span>⌄</span>
        </button>
        <div>
            <div class="choicelist">
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
                <div class="dropdown-scrollwindow">
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
    <div v-if="input_type == 'document_choice_taken'" class="inputfield">
      
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
      
      <div class="dropdown-scrollwindow">
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

    <div v-if="input_type == 'document_choice_modern'" class="inputfield">
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

        <div class="dropdown-scrollwindow">
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

    <div v-if="input_type == 'multi_choice_edit_delete'" class="inputfield">
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

    <div v-if="input_type == 'bifolia'" class="inputfield">
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
    publication: Boolean,
    content: Boolean,
    dating: Boolean,
    materiality: Boolean,
    measurement: Boolean,
    palaeography: Boolean,
    consanal: Boolean,
    provenance: Boolean,
});

let checked = ref(props.modelValue);
let publication_checked = ref(props.publication);
let content_checked = ref(props.content);
let dating_checked = ref(props.dating);
let materiality_checked = ref(props.materiality);
let measurement_checked = ref(props.measurement);
let palaeography_checked = ref(props.palaeography);
let consanal_checked = ref(props.consanal);
let provenance_checked = ref(props.provenance);

let bifolia = ref(props.modelValue);

let testval = ref();

const emit = defineEmits([
    "update:modelValue",
    "update:publication",
    "update:content",
    "update:dating",
    "update:materiality",
    "update:measurement",
    "update:palaeography",
    "update:consanal",
    "update:provenamce",
    "keyPressed",
    "newChange",
]);

function del_choice(id) {
    if (confirm("Do you really want to delete record #" + id + "?")) {
    }
}

function removechoice(choice) {
    this.checked = this.checked.filter((obj) => {
        return obj.id !== choice.id;
    });

    emit("update:modelValue", checked);
    emit("newChange");
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

<style></style>
