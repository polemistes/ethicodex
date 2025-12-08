<template>
    <div class="codexmenu">
        <button
            type="button"
            :class="activetab == 'general' ? 'selected' : 'unselected'"
            @click="activetab = 'general'"
        >
            General Information
        </button>

        <button
            type="button"
            :class="activetab == 'codicology' ? 'selected' : 'unselected'"
            @click="activetab = 'codicology'"
        >
            Codicology
        </button>

        <button
            type="button"
            :class="activetab == 'conservation' ? 'selected' : 'unselected'"
            @click="activetab = 'conservation'"
        >
            Conservation and Analysis
        </button>

        <button
            type="button"
            :class="activetab == 'provenance' ? 'selected' : 'unselected'"
            @click="activetab = 'provenance'"
        >
            Provenance
        </button>

        <button
            type="button"
            :class="activetab == 'images' ? 'selected' : 'unselected'"
            @click="activetab = 'images'"
        >
            Images
        </button>
    </div>

    <form @submit.prevent="submit" class="mainstyle">
        <button
            type="button"
            class="topbutton"
            v-if="props.auth == null ? 0 : props.auth.user.role.id >= 2 ? 1 : 0"
            @click="show_codex(document.id)"
            :disabled="form.processing"
        >
            Show Codex
        </button>

        <div class="topbutton">({{ props.current }} of {{ props.total }})</div>

        <button
            type="button"
            :disabled="next === -1 || form.processing"
            class="topbutton"
            @click="edit_codex(props.next)"
        >
            Next
        </button>

        <button
            type="button"
            :disabled="prev === -1 || form.processing"
            class="topbutton"
            @click="edit_codex(props.prev)"
        >
            Previous
        </button>

        <div class="nameheading">
            {{ document.standard_name }}
        </div>

        <input type="hidden" input_id="id" v-model="form.id" />
        <div v-if="activetab == 'general'" class="maincontainer">
            <fieldset class="editcodex_generalgrid">
                <legend class="sectionheading">Publication Info</legend>

                <div class="edit_published">
                    <label :for="published">Published</label>
                    <input
                        :id="published"
                        type="checkbox"
                        style="margin-left: 5px"
                        v-model="form.published"
                    />
                    <br>
                    <label :for="completed">Completed</label>
                    <input
                        :id="completed"
                        type="checkbox"
                        style="margin-left: 5px"
                        v-model="form.completed"
                    />
                </div>

                <div class="edit_standard">
                    <EthInput
                        input_type="text"
                        input_id="standard_name"
                        v-model="form.standard_name"
                    >
                        Standard Name
                    </EthInput>
                </div>

                <div class="edit_other">
                    <EthInput
                        class="edit_other"
                        input_type="textarea"
                        input_id="other_names"
                        v-model="form.other_names"
                        >Other Names</EthInput
                    >
                </div>

                <div class="edit_publ">
                    <EthInput
                        input_type="text"
                        input_id="publication"
                        v-model="form.publication"
                        >Publication</EthInput
                    >
                </div>

                <div class="edit_shelf">
                    <EthInput
                        input_type="text"
                        input_id="current_shelfmarks"
                        v-model="form.current_shelfmarks"
                        >Current Shelfmarks</EthInput
                    >
                </div>

                <div class="edit_trismegistos">
                    <EthInput
                        input_type="text"
                        input_id="trismegistos_id"
                        v-model="form.trismegistos_id"
                        >Trismegistos ID</EthInput
                    >
                </div>

                <div class="edit_bibliography">
                    <EthInput
                        input_type="textarea"
                        input_id="bibliography"
                        v-model="form.bibliography"
                        >Bibliography</EthInput
                    >
                </div>
            </fieldset>

            <fieldset class="editcodex_contentgrid">
                <legend class="sectionheading">Content</legend>

                <div class="edit_title">
                    <EthInput
                        input_type="text"
                        input_id="title"
                        v-model="form.title"
                        >Title of Work</EthInput
                    >
                </div>

                <div class="edit_author">
                    <EthInput
                        input_type="text"
                        input_id="ancient_author"
                        v-model="form.ancient_author"
                        >Author</EthInput
                    >
                </div>

                <div class="edit_work">
                    <label :for="input_id">Works</label>
                    <button
                        @click.prevent="dropdown = !dropdown"
                        class="dropdownbutton"
                    >
                        <span>Select</span>
                        <span>⌄</span>
                    </button>

                    <div v-if="dropdown" class="dropdown-content">
                        <div v-if="all_works.length > 12">
                            <label :for="search">Search:</label>
                            <input
                                type="text"
                                style="margin-left: 10px"
                                v-model="search"
                            />
                        </div>

                        <div class="dropdown-scrollwindow">
                            <div
                                v-for="choice in search_choices.sort((a, b) =>
                                    a.author.name.localeCompare(b.author.name)
                                )"
                                :key="choice.id"
                            >
                                <input
                                    type="checkbox"
                                    :id="choice.id"
                                    :value="choice"
                                    v-model="form.works"
                                    style="margin-right: 5px"
                                />
                                <label :title="choice.author.altnames">
                                    {{ choice.author.name }}
                                </label>
                                <label :title="choice.altnames"
                                    >{{ "| " + choice.name }}
                                </label>
                                <span v-if="
                                        form.works.some(
                                            (e) => e.id === choice.id
                                        )
                                    "> 
                                <label>| Passage:</label><input
                                    
                                    type="text"
                                    style="margin-left: 10px"
                                    v-model="choice.passages"
                                    @change="changepassage(choice)"
                                />
                                <label>| Comment:</label>
                                <input
                                    type="text"
                                    style="margin-left: 10px"
                                    v-model="choice.passage_comment"
                                    @change="changepassage(choice)"
                                />
                            </span>
                            </div>
                        </div>
                        <button
                            @click.prevent="dropdown = false"
                            class="closemenubutton"
                        >
                            Close
                        </button>
                    </div>
                   <div class="choicelist-table">
                    <table>
                        <tr><th></th><th>Author</th><th>Title</th><th>Passage</th><th>Comment</th></tr>
                        <tr
                            v-for="work in form.works.sort((a, b) =>
                                    a.author.name.localeCompare(b.author.name)
                                )"
                            :key="work.id"
                        >
            

                        <td><button
                                @click.prevent="removework(work)"
                                class="removebutton"
                                title="Delete"
                            >
                                ✕
                            </button>
                        </td>
                        <td>
                            <label :title="work.author.altnames">{{
                                work.author.name 
                            }}</label>
                        </td>
                        <td>
                            <label :title="work.altnames">{{
                                work.name
                            }}</label>
                        </td>
                        <td>
                            <label>{{ work.passages ? work.passages : "" }}</label>
                        </td>
                        <td>
                            <label>{{ work.passage_comment ? work.passage_comment : "" }}</label>
                        </td>
                        </tr>
                    </table>
                    </div>
                </div>

                <div class="edit_genre">
                    <EthInput
                        input_type="multi_choice"
                        input_id="genres"
                        :choices="genres_all"
                        v-model="form.genres"
                    >
                        Genres
                    </EthInput>
                </div>

                <div class="edit_content">
                    <EthInput
                        input_type="multi_choice"
                        input_id="tags"
                        :choices="tags_all"
                        v-model="form.tags"
                    >
                        Content Tags
                    </EthInput>
                </div>

                <div class="edit_contdesc">
                    <EthInput
                        input_type="textarea"
                        input_id="content_description"
                        v-model="form.content_description"
                        >Description of Content</EthInput
                    >
                </div>

                <div class="edit_language">
                    <EthInput
                        input_type="multi_choice"
                        input_id="languages"
                        :choices="languages_all"
                        v-model="form.languages"
                    >
                        Languages
                    </EthInput>
                </div>
            </fieldset>

            <fieldset class="editcodex_date">
                <legend class="sectionheading">Date</legend>
                <div class="edit_early">
                    <EthInput
                        input_type="year"
                        input_id="start_year"
                        v-model="form.start_year"
                        >Earliest Date</EthInput
                    >
                </div>

                <div class="edit_late">
                    <EthInput
                        input_type="year"
                        input_id="end_year"
                        v-model="form.end_year"
                        >Latest Date</EthInput
                    >
                </div>

                <div class="edit_basis">
                    <EthInput
                        input_type="multi_choice"
                        input_id="dating_methods"
                        :choices="dating_methods_all"
                        v-model="form.dating_methods"
                    >
                        Basis of Date
                    </EthInput>
                </div>

                <div class="edit_certainty">
                    <EthInput
                        input_type="single_choice"
                        input_id="dating_certainty"
                        :choices="dating_certainties"
                        v-model="form.dating_certainty_id"
                    >
                        Certainty of Date
                    </EthInput>
                </div>

                <div class="edit_datecomm">
                    <EthInput
                        input_type="textarea"
                        input_id="dating_comment"
                        v-model="form.dating_comment"
                        >Comments on Date</EthInput
                    >
                </div>
            </fieldset>

            <fieldset class="editcodex_comments">
                <legend class="sectionheading">Comments</legend>
                <div class="edit_internal">
                    <EthInput
                        input_type="textarea"
                        input_id="internal_comment"
                        v-model="form.internal_comment"
                        >Internal Comments</EthInput
                    >
                </div>

                <div class="edit_general">
                    <EthInput
                        input_type="textarea"
                        input_id="general_comment"
                        v-model="form.general_comment"
                        >General Comments</EthInput
                    >
                </div>
            </fieldset>

            <button
                :class="form.isDirty ? 'submitbutton_red' : 'submitbutton'"
                @click.prevent="submit"
                :disabled="form.processing"
            >
                Store All Changes
            </button>
        </div>

        <div v-if="activetab == 'codicology'" class="maincontainer">
            <fieldset class="editcodex_materiality">
                <legend class="sectionheading">Materiality</legend>
                <div class="edit_material">
                    <EthInput
                        input_type="single_choice"
                        input_id="material"
                        :choices="materials"
                        v-model="form.material_id"
                    >
                        Material
                    </EthInput>
                </div>

                <div class="edit_ink">
                    <EthInput
                        input_type="multi_choice"
                        input_id="inks"
                        :choices="inks_all"
                        v-model="form.inks"
                    >
                        Inks
                    </EthInput>
                </div>

                <div class="edit_cover">
                    <EthInput
                        input_type="single_choice"
                        input_id="cover"
                        :choices="covers"
                        v-model="form.cover_id"
                    >
                        Cover
                    </EthInput>
                </div>

                <div class="edit_structure">
                    <EthInput
                        input_type="single_choice"
                        input_id="quire_structure"
                        :choices="quire_structures"
                        v-model="form.quire_structure_id"
                    >
                        Quire Structure
                    </EthInput>
                </div>

                <div v-if="form.quire_structure_id == 1" class="edit_numquire">
                    <label :for="bifolia">Number of Bifolia in the Quire</label>
                    <div>
                        <p>
                            <input
                                type="number"
                                id="bifolia"
                                min="1"
                                required
                                v-model="form.bifolia[0]"
                            />
                        </p>
                    </div>
                </div>

                <div v-if="form.quire_structure_id == 2" class="edit_numquire">
                    <EthInput
                        input_type="number"
                        input_id="quire_number"
                        v-model="form.quire_number"
                        >Number of Quires</EthInput
                    >
                    <div class="edit_quirenum">
                        <label v-if="form.quire_number" :for="bifolia"
                            >Number of Bifolia in each Quire</label
                        >
                        <div
                            v-for="n in range(1, parseInt(form.quire_number))"
                            :key="n"
                        >
                            <p>
                                {{ n }}:
                                <input
                                    type="number"
                                    min="1"
                                    required
                                    v-model="form.bifolia[n - 1]"
                                />
                            </p>
                        </div>
                    </div>
                </div>

                <div v-if="form.quire_structure_id == 3" class="edit_numquire">
                    <EthInput
                        input_type="number"
                        input_id="quire_number"
                        v-model="form.quire_number"
                        >Number of Quires</EthInput
                    >
                </div>
                <div v-if="form.quire_structure_id == 3" class="edit_quirenum">
                    <label :for="bifolia"
                        >Number of Bifolia in each Quire</label
                    >
                    <div>
                        <p>
                            <input
                                type="number"
                                id="bifolia"
                                min="1"
                                required
                                v-model="form.bifolia[0]"
                            />
                        </p>
                    </div>
                </div>

                <div class="edit_gregory">
                    <EthInput
                        input_type="single_choice"
                        input_id="gregorys_rule"
                        :choices="gregorys_rules"
                        v-model="form.gregorys_rule_id"
                    >
                        Gregory's Rule
                    </EthInput>
                </div>

                <div class="edit_bindingdesc">
                    <EthInput
                        input_type="textarea"
                        input_id="binding_description"
                        v-model="form.binding_description"
                        >Binding Description</EthInput
                    >
                </div>
                <div class="edit_inksdesc">
                    <EthInput
                        input_type="textarea"
                        input_id="inks_description"
                        v-model="form.inks_description"
                        >Inks Description</EthInput
                    >
                </div>
                <div class="edit_coverdesc">
                    <EthInput
                        input_type="textarea"
                        input_id="cover_description"
                        v-model="form.cover_description"
                        >Cover Description</EthInput
                    >
                </div>
                <div class="edit_quirecomm">
                    <EthInput
                        input_type="textarea"
                        input_id="quire_comment"
                        v-model="form.quire_comment"
                        >Comments on Quire(s)</EthInput
                    >
                </div>

                <div class="edit_gregorycomm">
                    <EthInput
                        input_type="textarea"
                        input_id="gregorys_rule_comment"
                        v-model="form.gregorys_rule_comment"
                        >Comments on Gregory's Rule</EthInput
                    >
                </div>
            </fieldset>

            <fieldset class="editcodex_measurements">
                <legend class="sectionheading">Measurements</legend>
                <div class="edit_dimknown">
                    <label :for="page_dimensions_known"
                        >Page Dimensions Known</label
                    >
                    <input
                        :id="page_dimensions_known"
                        type="checkbox"
                        style="margin-left: 5px"
                        v-model="form.page_dimensions_known"
                    />
                </div>

                <template v-if="!form.page_dimensions_known">
                    <div class="edit_pagewidth">
                        <EthInput
                            input_type="text"
                            input_id="fragment_width"
                            v-model="form.fragment_width"
                            >Fragment Width (cm)</EthInput
                        >
                    </div>
                    <div class="edit_pageheight">
                        <EthInput
                            input_type="text"
                            input_id="fragment_height"
                            v-model="form.fragment_height"
                            >Fragment Height (cm)</EthInput
                        >
                    </div>
                </template>

                <template v-if="form.page_dimensions_known">
                    <div class="edit_dimest">
                        <label :for="page_dimensions_estimated"
                            >Estimated</label
                        >
                        <input
                            :id="page_dimensions_estimated"
                            type="checkbox"
                            style="margin-left: 5px"
                            v-model="form.page_dimensions_estimated"
                        />
                    </div>

                    <div class="edit_pagewidth">
                        <EthInput
                            input_type="text"
                            input_id="full_page_width"
                            v-model="form.full_page_width"
                            >Full Page Width (cm)</EthInput
                        >
                    </div>
                    <div class="edit_pageheight">
                        <EthInput
                            input_type="text"
                            input_id="full_page_height"
                            v-model="form.full_page_height"
                            >Full Page Height (cm)</EthInput
                        >
                    </div>
                    <div class="edit_pratio">
                        <EthRatio
                            :dividend="form.full_page_width"
                            :divisor="form.full_page_height"
                            >Ratio</EthRatio
                        >
                    </div>
                </template>
                <div class="edit_stable">
                    <div>
                        <label :for="textbox_size_stable"
                            >Size of Textbox is Stable</label
                        >
                        <input
                            :id="textbox_size_stable"
                            type="checkbox"
                            style="margin-left: 5px"
                            v-model="form.textbox_size_stable"
                        />
                    </div>
                </div>

                <div class="edit_uppmarg">
                    <EthInput
                        input_type="text"
                        input_id="upper_margin"
                        v-model="form.upper_margin"
                        >Upper Margin (cm)</EthInput
                    >
                </div>
                <div class="edit_lowmarg">
                    <EthInput
                        input_type="text"
                        input_id="lower_margin"
                        v-model="form.lower_margin"
                        >Lower Margin (cm)</EthInput
                    >
                </div>
                <div class="edit_ulratio">
                    <EthRatio
                        :dividend="form.upper_margin"
                        :divisor="form.lower_margin"
                        >Ratio</EthRatio
                    >
                </div>

                <div class="edit_innmarg">
                    <EthInput
                        input_type="text"
                        input_id="inner_margin"
                        v-model="form.inner_margin"
                        >Inner Margin (cm)</EthInput
                    >
                </div>
                <div class="edit_outmarg">
                    <EthInput
                        input_type="text"
                        input_id="outer_margin"
                        v-model="form.outer_margin"
                        >Outer Margin (cm)</EthInput
                    >
                </div>
                <div class="edit_ioratio">
                    <EthRatio
                        :dividend="form.inner_margin"
                        :divisor="form.outer_margin"
                        >Ratio</EthRatio
                    >
                </div>

                <div class="edit_blockwidth">
                    <EthInput
                        input_type="text"
                        input_id="full_text_block_width"
                        v-model="form.full_text_block_width"
                        >Full Text Block Width (cm)</EthInput
                    >
                </div>
                <div class="edit_blockheight">
                    <EthInput
                        input_type="text"
                        input_id="full_text_block_height"
                        v-model="form.full_text_block_height"
                        >Full Text Block Height (cm)</EthInput
                    >
                </div>
                <div class="edit_bratio">
                    <EthRatio
                        :dividend="form.full_text_block_width"
                        :divisor="form.full_text_block_height"
                        >Ratio</EthRatio
                    >
                </div>
                <div class="edit_columns">
                    <EthInput
                        input_type="text"
                        input_id="columns"
                        v-model="form.columns"
                        >Number of Columns</EthInput
                    >
                </div>
                <div class="edit_columnlines">
                    <EthInput
                        input_type="text"
                        input_id="columnlines"
                        v-model="form.columnlines"
                        >Lines per Column</EthInput
                    >
                </div>

                <div class="edit_meascomm">
                    <EthInput
                        input_type="textarea"
                        input_id="measurement_comment"
                        v-model="form.measurement_comment"
                        >Comments on Measurements</EthInput
                    >
                </div>

                <div class="edit_textblockcomm">
                    <EthInput
                        input_type="textarea"
                        input_id="full_text_block_comment"
                        v-model="form.full_text_block_comment"
                        >Comments on Full Text Block</EthInput
                    >
                </div>
            </fieldset>

            <fieldset class="editcodex_textualfeatures">
                <legend class="sectionheading">Textual Features</legend>
                <div class="edit_scripts">
                    <EthInput
                        input_type="multi_choice_scripts"
                        input_id="scripts"
                        :choices="scripts_all"
                        v-model="form.scripts"
                    >
                        Scripts
                    </EthInput>
                </div>
                <div class="edit_numhands">
                    <EthInput
                        input_type="number"
                        input_id="hand_number"
                        v-model="form.hand_number"
                        >Number of Hands</EthInput
                    >
                </div>
                <div class="edit_scriptdesc">
                    <EthInput
                        input_type="textarea"
                        input_id="script_description"
                        v-model="form.script_description"
                        >Description of Scripts</EthInput
                    >
                </div>
                <div class="edit_paratext">
                    <EthInput
                        input_type="multi_choice"
                        input_id="paratexts"
                        :choices="paratexts_all"
                        v-model="form.paratexts"
                    >
                        Paratexts
                    </EthInput>
                </div>

                <div class="edit_paradesc">
                    <EthInput
                        input_type="textarea"
                        input_id="paratext_description"
                        v-model="form.paratext_description"
                        >Description of Paratexts</EthInput
                    >
                </div>
                <div class="edit_punctuation">
                    <EthInput
                        input_type="multi_choice"
                        input_id="punctuations"
                        :choices="punctuations_all"
                        v-model="form.punctuations"
                    >
                        Punctuation
                    </EthInput>
                </div>
                <div class="edit_punctuationdesc">
                    <EthInput
                        input_type="textarea"
                        input_id="punctuation_description"
                        v-model="form.punctuation_description"
                        >Description of Punctuation</EthInput
                    >
                </div>
                <div class="edit_diacritics">
                    <EthInput
                        input_type="multi_choice"
                        input_id="diacritics"
                        :choices="diacritics_all"
                        v-model="form.diacritics"
                    >
                        Diacritics
                    </EthInput>
                </div>
                <div class="edit_diacritdesc">
                    <EthInput
                        input_type="textarea"
                        input_id="diacritic_description"
                        v-model="form.diacritic_description"
                        >Description of Diacritics</EthInput
                    >
                </div>
                <div class="edit_critical">
                    <EthInput
                        input_type="multi_choice"
                        input_id="critical_symbols"
                        :choices="critical_symbols_all"
                        v-model="form.critical_symbols"
                    >
                        Critical Symbols
                    </EthInput>
                </div>
                <div class="edit_criticaldesc">
                    <EthInput
                        input_type="textarea"
                        input_id="critical_symbols_description"
                        v-model="form.critical_symbols_description"
                        >Description of Critical Symbols</EthInput
                    >
                </div>
                <div class="edit_decorative">
                    <EthInput
                        input_type="multi_choice"
                        input_id="decorations"
                        :choices="decorations_all"
                        v-model="form.decorations"
                    >
                        Decorative Symbols
                    </EthInput>
                </div>
                <div class="edit_decodesc">
                    <EthInput
                        input_type="textarea"
                        input_id="decoration_description"
                        v-model="form.decoration_description"
                        >Description of Decorative Symbols</EthInput
                    >
                </div>
                <div class="edit_pagination">
                    <EthInput
                        input_type="single_choice"
                        input_id="pagination"
                        :choices="paginations"
                        v-model="form.pagination_id"
                    >
                        Pagination
                    </EthInput>
                </div>
                <div class="edit_paginationdesc">
                    <EthInput
                        input_type="textarea"
                        input_id="pagination_description"
                        v-model="form.pagination_description"
                        >Description of Pagination</EthInput
                    >
                </div>
                <div class="edit_quiresig">
                    <EthInput
                        input_type="single_choice"
                        input_id="quire_signature"
                        :choices="quire_signatures"
                        v-model="form.quire_signature_id"
                    >
                        Quire Signatures
                    </EthInput>
                </div>
                <div class="edit_quiresigdesc">
                    <EthInput
                        input_type="textarea"
                        input_id="quire_signatures_description"
                        v-model="form.quire_signatures_description"
                        >Description of Quire Signatures</EthInput
                    >
                </div>
            </fieldset>

            <button
                :class="form.isDirty ? 'submitbutton_red' : 'submitbutton'"
                @click.prevent="submit"
                :disabled="form.processing"
            >
                Store All Changes
            </button>
        </div>

        <div v-if="activetab == 'conservation'" class="maincontainer">
            <fieldset class="editcodex_conservanalys">
                <legend class="sectionheading">
                    Conservation and Analysis
                </legend>
                <div class="edit_storage">
                    <EthInput
                        input_type="single_choice"
                        input_id="storage_condition"
                        :choices="storage_conditions"
                        v-model="form.storage_condition_id"
                    >
                        Storage Condition
                    </EthInput>
                </div>
                <div class="edit_conshist">
                    <EthInput
                        input_type="textarea"
                        input_id="conservation_history"
                        v-model="form.conservation_history"
                        >Conservation History</EthInput
                    >
                </div>
                <div class="edit_scientific">
                    <EthInput
                        input_type="multi_choice_stack"
                        input_id="analyses"
                        :choices="analyses_all"
                        v-model="form.analyses"
                    >
                        Scientific Analysis
                    </EthInput>
                </div>
                <div class="analyscomm">
                    <EthInput
                        input_type="textarea"
                        input_id="analyses_comment"
                        v-model="form.analyses_comment"
                        >Comments on Analysis</EthInput
                    >
                </div>
            </fieldset>

            <button
                :class="form.isDirty ? 'submitbutton_red' : 'submitbutton'"
                @click.prevent="submit"
                :disabled="form.processing"
            >
                Store All Changes
            </button>
        </div>

        <div v-if="activetab == 'provenance'" class="maincontainer">
            <fieldset class="editcodex_provenance">
                <legend class="sectionheading">Provenance</legend>
                <div class="edit_sciex">
                    <label :for="scientifically_excavated"
                        >Excavated by Permit</label
                    >
                    <input
                        :id="scientifically_excavated"
                        type="checkbox"
                        style="margin-left: 5px"
                        v-model="form.scientifically_excavated"
                    />
                </div>

                <div class="edit_excomm">
                    <EthInput
                        input_type="textarea"
                        input_id="excavation_comment"
                        v-model="form.excavation_comment"
                    >
                        Comments on Excavation
                    </EthInput>
                </div>

                <div class="edit_anciprov">
                    <EthInput
                        input_type="single_choice_hier"
                        input_id="ancient_provenance"
                        :choices="ancient_provenances"
                        v-model="form.ancient_provenance_id"
                    >
                        Ancient Provenance
                    </EthInput>
                </div>

                <div class="edit_ancicomm">
                    <EthInput
                        input_type="textarea"
                        input_id="ancient_provenance_comment"
                        v-model="form.ancient_provenance_comment"
                    >
                        Comments on Ancient Provenance
                    </EthInput>
                </div>

                <div class="edit_legalclass">
                    <EthInput
                        input_type="single_choice"
                        input_id="legal_classification"
                        :choices="legal_classifications"
                        v-model="form.legal_classification_id"
                    >
                        Legal Classification
                    </EthInput>
                </div>

                <div class="edit_legalcomm">
                    <EthInput
                        input_type="textarea"
                        input_id="legal_classification_explanation"
                        v-model="form.legal_classification_explanation"
                        >Comments on Legal Classification</EthInput
                    >
                </div>

                <div class="edit_transactions">
                    <EthInput
                        input_type="transactions_choice"
                        input_id="transactions"
                        :choices="transactions_all"
                        v-model="form.transactions"
                    >
                        Transactions
                    </EthInput>
                </div>

                <div class="edit_collections">
                    <EthInput
                        input_type="multi_choice_stack"
                        input_id="collections"
                        :choices="collections_all"
                        v-model="form.collections"
                    >
                        Current Collections
                    </EthInput>
                </div>


            </fieldset>
            <button
                :class="form.isDirty ? 'submitbutton_red' : 'submitbutton'"
                @click.prevent="submit"
                :disabled="form.processing"
            >
                Store All Changes
            </button>
        </div>

        <div v-if="activetab == 'images'" class="maincontainer">
            <fieldset class="editcodex_images">
                <legend class="sectionheading">Images</legend>
                <div class="edit_img">
                    <EthInput
                        input_type="textarea"
                        input_id="images_info"
                        v-model="form.images_info"
                        >Image Information</EthInput
                    >
                </div>

                <button
                    :class="form.isDirty ? 'submitbutton_red' : 'submitbutton'"
                    @click.prevent="submit"
                    :disabled="form.processing"
                >
                    Store All Changes
                </button>

                <label class="labelpadding" for="images">Upload Images</label>

                <div>
                    <input
                        :disabled="form.isDirty"
                        type="file"
                        @change="addimages($event.target.files)"
                        multiple
                        ref="loadImages"
                    />

                    <span v-if="form.isDirty">
                        (Please store all changes to upload images.)
                    </span>
                </div>

                <div class="codex_images_rows">
                    <div
                        class="editcodex_imagebox"
                        v-for="(image, index) in form.images"
                        :key="image.id"
                    >
                        <a :href="'/storage/' + image.filename" target="_blank">
                            <div class="codex_image_container">
                                <img :src="'/storage/' + image.filename" />
                            </div>
                        </a>

                        <button
                            v-if="!form.isDirty"
                            @click.prevent="delimage(image)"
                        >
                            Delete
                        </button>
                        <button
                            v-if="form.isDirty"
                            @click.prevent="delalert(image)"
                        >
                            Delete
                        </button>

                        <div>
                            <label>Micrograph</label>
                            <input
                                type="checkbox"
                                true-value="1"
                                false-value="0"
                                style="margin-left: 5px"
                                v-model="image.micrograph"
                            />
                            <label style="padding-left: 10px">Visible</label>
                            <input
                                type="checkbox"
                                true-value="1"
                                false-value="0"
                                style="margin-left: 5px"
                                v-model="image.visible"
                            />

                        </div>

                        <EthInput
                            input_type="imagecom"
                            :input_id="'image_description_' + index"
                            v-model="image.description"
                        >
                            Description
                        </EthInput>

                        <EthInput
                            input_type="text"
                            input_id="image_source"
                            v-model="image.source"
                        >
                            Source
                        </EthInput>

                        <EthInput
                            input_type="single_choice"
                            input_id="license"
                            :choices="licenses"
                            v-model="image.license_id"
                        >
                            License:
                        </EthInput>
                    </div>
                </div>
            </fieldset>
        </div>
    </form>
</template>

<script setup>
import { router } from "@inertiajs/vue3";
import { reactive, ref, computed, onMounted, onUpdated } from "vue";
import { createApp } from "vue";

import { useForm } from "@inertiajs/vue3";

import EthInput from "../Components/EthInput.vue";
import EthRatio from "../Components/EthRatio.vue";

const props = defineProps({
    analyses: Array,
    analyses_all: Array,
    ancient_provenance: Object,
    ancient_provenances: Array,
    ancient_provenance_certainty: Object,
    ancient_provenance_certainties: Array,
    cover: Object,
    covers: Array,
    critical_symbols: Array,
    critical_symbols_all: Array,
    dating_certainty: Object,
    dating_certainties: Array,
    dating_methods: Array,
    dating_methods_all: Array,
    decorations: Array,
    decorations_all: Array,
    diacritics: Array,
    diacritics_all: Array,
    document: Object,
    genres: Array,
    genres_all: Array,
    gregorys_rule: Object,
    gregorys_rules: Array,
    images: Array,
    inks_all: Array,
    inks: Array,
    languages: Array,
    languages_all: Array,
    legal_classification: Object,
    legal_classifications: Array,
    licenses: Array,
    material: Object,
    materials: Array,
    collections: Array,
    collections_all: Array,
    newimages: Array,
    pagination: Object,
    paginations: Array,
    paratexts: Array,
    paratexts_all: Array,
    published: Boolean,
    punctuations: Array,
    punctuations_all: Array,
    transactions: Array,
    transactions_all: Array,
    quire_signature: Array,
    quire_signatures: Array,
    quire_structure: Object,
    quire_structures: Array,
    scripts: Array,
    scripts_all: Array,
    storage_condition: Object,
    storage_conditions: Array,
    tags: Array,
    tags_all: Array,
    works: Array,
    works_all: Array,

    show_publication: Boolean,
    show_content: Boolean,
    show_dating: Boolean,
    show_materiality: Boolean,
    show_measurement: Boolean,
    show_palaeography: Boolean,
    show_consanal: Boolean,
    show_provenance: Boolean,

    fulltext: String,
    /* Publication */
    standard_name: String,
    publication: String,
    current_shelfmarks: String,
    trismegistos_id: String,
    completed: Boolean,
    published: Boolean,
    imagelinks: Boolean,
    imagesonsite: Boolean,
    /* Content */
    title: String,
    ancient_author: String,
    languages_search: Array,
    languages_incl: Boolean,
    genres_search: Array,
    genres_incl: Boolean,
    tags_search: Array,
    tags_incl: Boolean,
    /* Dating */
    earliest_date: Number,
    latest_date: Number,
    exclusive_date: Boolean,
    dating_methods_search: Array,
    dating_methods_incl: Boolean,
    dating_certainties_search: Array,
    /* Materiality */
    materials_search: Array,
    gregorys_rules_search: Array,
    inks_search: Array,
    inks_incl: Boolean,
    inks_only: Boolean,
    covers_search: Array,
    quire_structures_search: Array,
    quirenum_min: Number,
    quirenum_max: Number,
    bifolianum_min: Number,
    bifolianum_max: Number,
    /* Measurement */
    include_estimated: Boolean,
    full_page_width_min: Number,
    full_page_width_max: Number,
    full_page_height_min: Number,
    full_page_height_max: Number,
    full_text_block_width_min: Number,
    full_text_block_width_max: Number,
    full_text_block_height_min: Number,
    full_text_block_height_max: Number,
    upper_margin_min: Number,
    upper_margin_max: Number,
    lower_margin_min: Number,
    lower_margin_max: Number,
    inner_margin_min: Number,
    inner_margin_max: Number,
    outer_margin_min: Number,
    outer_margin_max: Number,
    full_page_ratio_min: Number,
    full_page_ratio_max: Number,
    full_text_block_ratio_min: Number,
    full_text_block_ratio_max: Number,
    uplow_margins_ratio_min: Number,
    uplow_margins_ratio_max: Number,
    inout_margins_ratio_min: Number,
    inout_margins_ratio_max: Number,
    columns_min: Number,
    columns_max: Number,
    columnlines_min: Number,
    columnlines_max: Number,

    /* Palaeography */
    hand_number_min: Number,
    hand_number_max: Number,
    scripts_search: Array,
    scripts_incl: Boolean,
    diacritics_search: Array,
    diacritics_incl: Boolean,
    punctuations_search: Array,
    punctuations_incl: Boolean,
    paratexts_search: Array,
    paratexts_incl: Boolean,
    decorations_search: Array,
    decorations_incl: Boolean,
    critical_symbols_search: Array,
    critical_symbols_incl: Boolean,
    paginations_search: Array,
    quire_signatures_search: Array,
    /* Conservation and Analysis */
    storage_conditions_search: Array,
    analyses_search: Array,
    analyses_incl: Boolean,
    /* Provenance */
    scientifically_excavated: Number,
    ancient_provenances_search: Array,
    ancient_provenance_certainties_search: Array,
    transactions_search: Array,
    transactions_incl: Boolean,
    transaction_parties_search: Array,
    transaction_parties_incl: Boolean,
    legal_classifications_search: Array,

    sortfield: String,
    reverse: Number,

    tab: String,
    prev: Number,
    next: Number,
    current: Number,
    total: Number,

    auth: Object,
});

const wks = [];

for (const w of props.works) {
    wks.push({
        id: w.id,
        name: w.name,
        altnames: w.altnames,
        author_id: w.author_id,
        author: w.author, 
        description: w.description,
        passages: w.pivot.passages,
        passage_comment: w.pivot.passage_comment,
    }); 
}

const form = useForm("EditCodex", {
    id: props.document.id,
    published: props.document.published == 1 ? true : false,
    completed: props.document.completed == 1 ? true : false,
    standard_name: props.document.standard_name,
    other_names: props.document.other_names,
    publication: props.document.publication,
    current_shelfmarks: props.document.current_shelfmarks,
    trismegistos_id: props.document.trismegistos_id,
    works: wks,
    title: props.document.title,
    genres: props.genres,
    scientifically_excavated:
        props.document.scientifically_excavated == 1 ? true : false,
    excavation_comment: props.document.excavation_comment,
    ancient_author: props.document.ancient_author,
    tags: props.tags,
    content_description: props.document.content_description,
    start_year: props.document.start_year,
    end_year: props.document.end_year,
    languages: props.languages,
    dating_methods: props.dating_methods,
    dating_certainty_id: props.document.dating_certainty_id,
    dating_comment: props.document.dating_comment,
    bibliography: props.document.bibliography,
    collections: props.collections,
    internal_comment: props.document.internal_comment,
    general_comment: props.document.general_comment,
    material_id: props.document.material_id,
    page_dimensions_known:
        props.document.page_dimensions_known == 1 ? true : false,
    page_dimensions_estimated:
        props.document.page_dimensions_estimated == 1 ? true : false,        
    fragment_width: props.document.fragment_width,
    fragment_height: props.document.fragment_height,
    full_page_width: props.document.full_page_width,
    full_page_height: props.document.full_page_height,
    textbox_size_stable: props.document.textbox_size_stable == 1 ? true : false,
    upper_margin: props.document.upper_margin,
    lower_margin: props.document.lower_margin,
    inner_margin: props.document.inner_margin,
    outer_margin: props.document.outer_margin,
    full_text_block_width: props.document.full_text_block_width,
    full_text_block_height: props.document.full_text_block_height,
    full_text_block_comment: props.document.full_text_block_comment,
    columns: props.document.columns,
    columnlines: props.document.columnlines,
    measurement_comment: props.document.measurement_comment,
    scripts: props.scripts,
    hand_number: props.document.hand_number,
    script_description: props.document.script_description,
    paratexts: props.paratexts,
    paratext_description: props.document.paratext_description,
    punctuations: props.punctuations,
    punctuation_description: props.document.punctuation_description,
    diacritics: props.diacritics,
    diacritic_description: props.document.diacritic_description,
    critical_symbols: props.critical_symbols,
    critical_symbols_description: props.document.critical_symbols_description,
    decorations: props.decorations,
    decoration_description: props.document.decoration_description,
    pagination_id: props.document.pagination_id,
    pagination_description: props.document.pagination_description,
    cover_id: props.document.cover_id,
    cover_description: props.document.cover_description,
    inks: props.inks,
    inks_description: props.document.inks_description,
    quire_signature_id: props.document.quire_signature_id,
    quire_signatures_description: props.document.quire_signatures_description,
    quire_structure_id: props.document.quire_structure_id,
    quire_number: props.document.quire_number,
    bifolia: props.document.bifolia ? JSON.parse(props.document.bifolia) : [],
    quire_comment: props.document.quire_comment,
    binding_description: props.document.binding_description,
    gregorys_rule_id: props.document.gregorys_rule_id,
    gregorys_rule_comment: props.document.gregorys_rule_comment,
    storage_condition_id: props.document.storage_condition_id,
    conservation_history: props.document.conservation_history,
    analyses: props.analyses,
    analyses_comment: props.document.analyses_comment,
    ancient_provenance_id: props.document.ancient_provenance_id,
    ancient_provenance_certainty_id:
        props.document.ancient_provenance_certainty_id,
    ancient_provenance_comment: props.document.ancient_provenance_comment,
    modern_collections: props.modern_collections,
    legal_classification_id: props.document.legal_classification_id,
    legal_classification_explanation:
        props.document.legal_classification_explanation,
    transactions: props.transactions,
    images_info: props.document.images_info,
    images: props.images,

    show_publication: props.show_publication,
    show_content: props.show_content,
    show_dating: props.show_dating,
    show_materiality: props.show_materiality,
    show_measurement: props.show_measurement,
    show_palaeography: props.show_palaeography,
    show_consanal: props.show_consanal,
    show_provenance: props.show_provenance,

    /* Search Fields */
    fulltext: props.fulltext,
    /* Publication */
    s_standard_name: props.standard_name,
    s_publication: props.publication,
    s_current_shelfmarks: props.current_shelfmarks,
    s_trismegistos_id: props.trismegistos_id,
    s_completed: props.completed,
    s_published: props.published,
    s_imagelinks: props.imagelinks,
    s_imagesonsite: props.imagesonsite,
    /* Content */
    s_title: props.title,
    s_ancient_author: props.ancient_author,
    s_languages: props.languages_search ? props.languages_search : [],
    s_languages_incl: props.languages_incl,
    s_genres: props.genres_search ? props.genres_search : [],
    s_genres_incl: props.genres_incl,
    s_tags: props.tags_search ? props.tags_search : [],
    s_tags_incl: props.tags_incl,
    /* Dating */
    s_earliest_date: props.earliest_date,
    s_latest_date: props.latest_date,
    s_exclusive_date: props.exclusive_date,
    s_dating_methods: props.dating_methods_search
        ? props.dating_methods_search
        : [],
    s_dating_methods_incl: props.dating_methods_incl,
    s_dating_certainties: props.dating_certainties_search
        ? props.dating_certainties_search
        : [],
    /* Materiality */
    s_materials: props.materials_search ? props.materials_search : [],
    s_gregorys_rules: props.gregorys_rules_search
        ? props.gregorys_rules_search
        : [],
    s_inks: props.inks_search ? props.inks_search : [],
    s_inks_incl: props.inks_incl,
    s_inks_only: props.inks_only,
    s_covers: props.covers_search ? props.covers_search : [],
    s_quire_structures: props.quire_structures_search
        ? props.quire_structures_search
        : [],
    s_quirenum_min: props.quirenum_min,
    s_quirenum_max: props.quirenum_max,
    s_bifolianum_min: props.bifolianum_min,
    s_bifolianum_max: props.bifolianum_max,
    /* Measurement */
    s_include_estimated: props.include_estimated,
    s_full_page_width_min: props.full_page_width_min,
    s_full_page_width_max: props.full_page_width_max,
    s_full_page_height_min: props.full_page_height_min,
    s_full_page_height_max: props.full_page_height_max,
    s_full_text_block_width_min: props.full_text_block_width_min,
    s_full_text_block_width_max: props.full_text_block_width_max,
    s_full_text_block_height_min: props.full_text_block_height_min,
    s_full_text_block_height_max: props.full_text_block_height_max,
    s_upper_margin_min: props.upper_margin_min,
    s_upper_margin_max: props.upper_margin_max,
    s_lower_margin_min: props.lower_margin_min,
    s_lower_margin_max: props.lower_margin_max,
    s_inner_margin_min: props.inner_margin_min,
    s_inner_margin_max: props.inner_margin_max,
    s_outer_margin_min: props.outer_margin_min,
    s_outer_margin_max: props.outer_margin_max,
    s_full_page_ratio_min: props.full_page_ratio_min,
    s_full_page_ratio_max: props.full_page_ratio_max,
    s_full_text_block_ratio_min: props.full_text_block_ratio_min,
    s_full_text_block_ratio_max: props.full_text_block_ratio_max,
    s_uplow_margins_ratio_min: props.uplow_margins_ratio_min,
    s_uplow_margins_ratio_max: props.uplow_margins_ratio_max,
    s_inout_margins_ratio_min: props.inout_margins_ratio_min,
    s_inout_margins_ratio_max: props.inout_margins_ratio_max,
    s_columns_min: props.columns_min,
    s_columns_max: props.columns_max,
    s_columnlines_min: props.columnlines_min,
    s_columnlines_max: props.columnlines_max,

    /* Palaeography */
    s_hand_number_min: props.hand_number_min,
    s_hand_number_max: props.hand_number_max,
    s_scripts: props.scripts_search ? props.scripts_search : [],
    s_scripts_incl: props.scripts_incl,
    s_diacritics: props.diacritics_search ? props.diacritics_search : [],
    s_diacritics_incl: props.diacritics_incl,
    s_punctuations: props.punctuations_search ? props.punctuations_search : [],
    s_punctuations_incl: props.punctuations_incl,
    s_paratexts: props.paratexts_search ? props.paratexts_search : [],
    s_paratexts_incl: props.paratexts_incl,
    s_decorations: props.decorations_search ? props.decorations_search : [],
    s_decorations_incl: props.decorations_incl,
    s_critical_symbols: props.critical_symbols_search
        ? props.critical_symbols_search
        : [],
    s_critical_symbols_incl: props.critical_symbols_incl,
    s_paginations: props.paginations_search ? props.paginations_search : [],
    s_quire_signatures: props.quire_structures_search
        ? props.quire_structures_search
        : [],
    /* Conservation and Analysis */
    s_storage_conditions: props.storage_conditions_search
        ? props.storage_conditions_search
        : [],
    s_analyses: props.analyses_search ? props.analyses_search : [],
    s_analyses_incl: props.analyses_incl,
    /* Provenance */
    s_scientifically_excavated: props.scientifically_excavated,
    s_ancient_provenances: props.ancient_provenances_search
        ? props.ancient_provenances_search
        : [],
    s_ancient_provenance_certainties:
        props.ancient_provenance_certainties_search
            ? props.ancient_provenance_certainties_search
            : [],
    s_transactions: props.transactions_search ? props.transactions_search : [],
    s_transactions_incl: props.transaction_parties_incl,
    s_transaction_parties: props.transaction_parties_search
        ? props.transaction_parties_search
        : [],
    s_transaction_parties_incl: props.transaction_parties_incl,
    s_legal_classifications: props.legal_classifications_search
        ? props.legal_classifications_search
        : [],
    sortfield: props.sortfield ? props.sortfield : 4,
    reverse: props.reverse,
    tab: props.tab ? props.tab : "general",
});

let removeStartEventListener = router.on("before", (event) => {
    if (form.isDirty && !submitted) {
        if (
            confirm(
                "You have unsaved data in your form. Are you sure you want to leave the page?"
            )
        ) {
            removeStartEventListener();
            window.onbeforeunload = null;
            window.onpopstate = null;
        } else if (submitted) {
            removeStartEventListener();
            window.onbeforeunload = null;
            window.onpopstate = null;
        } else {
            return false;
        }
    }
});

window.onbeforeunload = (s) => (form.isDirty ? "" : null);

window.onpopstate = function (event) {
    removeStartEventListener();
    window.onbeforeunload = null;
    window.onpopstate = null;
    if (form.isDirty) {
        if (
            !confirm(
                "You have unsaved data. Do you really want to leave the page?"
            )
        ) {
            window.history.go(1);
        }
    }
};

onMounted(() => {
    if (!form.bifolia) {
        form.bifolia[0] = 0;
    }
});

onUpdated(() => {
    if (props.current == -1) {
        if (!alreadygone) {
            alert(
                "The current codex no longer matches the search criteria. Going back to the search results."
            );
            window.history.go(-1);
            alreadygone = true;
        }
    }
});

const loadImages = ref(null);

let submitted = false;
let activetab = ref(props.tab ? props.tab : "general");
let alreadygone = false;

function range(start, end) {
    var foo = [];
    for (var i = start; i <= end; i++) {
        foo.push(i);
    }
    return foo;
}

function delalert(image) {
    let image_id = image.id;
    if (
        confirm(
            "You may have unsaved changes that could be lost if you continue. Do you want to delete this image anyway?"
        )
    ) {
        router.post(
            "/delimage/" + image_id,
            { document_id: props.document.id },
            {
                preserveState: true,
                preserveScroll: true,
                resetOnSuccess: false,
                onSuccess: () => {
                    form.images = props.images.slice();
                },
            }
        );
    }
}

let search = ref("");
let dropdown = ref(false);

const search_choices = computed(() => {
    return search.value != ""
        ? all_works.filter(function (el) {
              return (
                  (el.name != null ? el.name.toLowerCase().includes(search.value.toLowerCase()) : null) ||
                  (el.altnames != null
                      ? el.altnames
                            .toLowerCase()
                            .includes(search.value.toLowerCase())
                      : null) ||
                  (el.description != null
                      ? el.description
                            .toLowerCase()
                            .includes(search.value.toLowerCase())
                      : null) ||
                  (el.author != null
                      ? el.author.name
                            .toLowerCase()
                            .includes(search.value.toLowerCase())
                      : null)
              );
          })
        : all_works;
});

function removework(choice) {
    form.works = form.works.filter((obj) => {
        return obj.id !== choice.id;
    });
}

const all_works = reactive([]);

for (let w of props.works_all) {
    all_works.push({
        id: w.id,
        name: w.name,
        altnames: w.altnames,
        author_id: w.author_id,
        author: w.author,
        description: w.description,
        passages: form.works.some((e) => e.id === w.id)
            ? form.works.find((item) => item.id === w.id).passages
            : null,
        passage_comment: form.works.some((e) => e.id === w.id)
            ? form.works.find((item) => item.id === w.id).passage_comment
            : null,
    });
}

function changepassage(w) {
    form.works.find((item) => item.id === w.id).passages = w.passages
    form.works.find((item) => item.id === w.id).passage_comment = w.passage_comment
}

function delimage(image) {
    let image_id = image.id;
    if (confirm("Do you want to delete this image?")) {
        router.post(
            "/delimage/" + image_id,
            { document_id: props.document.id },
            {
                preserveState: true,
                preserveScroll: true,
                resetOnSuccess: false,
                onSuccess: () => {
                    form.images = props.images.slice();
                },
            }
        );
    }
}

function addimages(imagefiles) {
    router.post(
        "/addimages",
        { images: imagefiles, document_id: props.document.id },
        {
            preserveState: true,
            preserveScroll: true,
            resetOnSuccess: false,
            onSuccess: () => {
                form.images = props.images.slice();
            },
        }
    );
    this.loadImages.value = null;
}

function show_codex(id) {
    form.tab = activetab.value;
    form.post("/codex_show/" + id, {
        queryStringArrayFormat: "indices",
        replace: true,
    });
}

function edit_codex(id) {
    const newform = form;
    newform.tab = activetab.value;
    newform.post("/codex_edit/" + id, {
        queryStringArrayFormat: "indices",
        preserveState: false,
        replace: true,
    });
}

function submit() {
    submitted = true;
    form.tab = activetab.value;
    form.put("/codex_edit/" + props.document.id, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
}
</script>

<style></style>
