<template>
  <div class="home">
    <h1>Carnet de recette</h1>
    <div class="newRecipes" @click="showForm = !showForm">+</div>
    <form v-show="showForm">
      <input type="text" placeholder="Nom de la recette" v-model="name">
      <div class="ingredientsFields" v-for="(ingredient, index) in newIngredientsRecipe" :key="index">
        <v-select @search="onSearchIngredient" :options="options">
          <template slot="no-options">
            pas d'ingredient
          </template>
          <template slot="option" slot-scope="option">
            <div class="d-center">
              {{ option.name }}
            </div>
          </template>
          <template slot="selected-option" slot-scope="option">
            <div class="selected d-center">
              {{ option.name }}
            </div>
          </template>
          <div class="spinner" v-show="spinner">Loading...</div>
        </v-select>
        <input type="number" placeholder="Quantiter" v-model="ingredient.quantity">
        <select v-model="ingredient.unity">
          <option value="gr">gr</option>
          <option value="pce">pce</option>
        </select> 
      </div>
      <button class="btn btn-classic" @click="addRecipeIngredient">ajouter un autre ingredient</button>
      <textarea name="method" v-model="method"/>
      <button @click="newRecipe">Soumettre le formulaire</button>
    </form>

    <hr>
    <div class="recipies" v-for="(recipe, i) in recipes" :key="i">
      <p> {{recipe.name}} </p>
      <div class="ingredients" v-for="(ingredient, j) in recipe.recipeIngredients" :key="j">
        <p> {{ingredient.ingredient.name}} {{ingredient.quantity}} {{ingredient.unity}} </p>
      </div>
      <hr>
    </div>
  </div>
</template>

<script>

import serverFile from '../api/ServerFile';
import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';
import _ from "lodash";

export default {
  name: 'HomeView',
  components: {
     vSelect
  },
  data() {
    return {
      showForm: false,
      name: '',
      method: '',
      ingredients: [],
      recipes: [],
      options: [],
      newIngredientsRecipe: [{
        name: '',
        quantity: Number,
        unity: '',
      }],
    }
  },
  methods: {
    addRecipeIngredient() {
      this.newIngredientsRecipe.push({
        name: '',
        quantity: Number,
        unity: ''
      })
    },
    newRecipe() {
      let params = { name: this.name,
                     method: this.method }
      serverFile.newRecipe(params).then(() => {
      })
    },
    getAllRecipes() {
      serverFile.getAllRecipes().then((response)=>{
        this.recipes = response['hydra:member']
      })
    },
    getAllIngredients() {
      serverFile.getAllIngredients().then((response) => {
        this.ingredients = response['hydra:member']
      })
    },
    onSearchIngredient(search, loading) {
      if(search.length) {
        loading(true);
        this.searchIngredient(loading, search, this);
      }
    },
    searchIngredient: _.debounce((loading, search, vm) => {
      serverFile.findIngredientByName(search).then((response) => {
        if (response) {
          vm.options = response['hydra:member']
          console.log(vm.options)
          loading(false);
        }
      })
    }, 350)
  },
  mounted: function() {
    this.getAllRecipes()
    this.getAllIngredients()
  }
}
</script>

<style lang="scss">
  .newRecipes {
    font-size: 2em;
    border-radius: 50%;
    background-color: cornflowerblue;
    color: white;
    border: 2px solid cornflowerblue;
    width: 50px;
    height: 50px;
    cursor: pointer;
    line-height: 52px;
    margin: 10px auto;
  }
</style>