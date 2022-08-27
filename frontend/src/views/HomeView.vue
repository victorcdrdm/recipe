<template>
  <div class="home">
    <h1 class="text-3xl underline">Carnet de recettes</h1>
    <div class="newRecipes" @click="form = !form">+</div>
    <form class="flex flex-col align-center m-auto w-3/6 " v-show="form">
      <label for="name" class="text-left mb-1">Nouvelle recette:</label>
      <input type="text" class="mb-3" name="name" placeholder="Ajouter le nom de votre recette" v-model="recipeName">
      <label for="" class="text-left mb-1">Rechere ou Ajouter un ingredient</label>
      <v-select class="mb-3" @search="onSearchIngredient" :options="options" v-model="ingredientSelected">
        <template slot="no-options" v-if="options">
          <hr>
          <div @click="addNewIngredient()">
            Cette ingredient n'existe pas encore, l'ajouter?
          </div>
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
        <div class="spinner" v-show="loader">Loading...</div>
      </v-select>
      <div class="flex justify-center mb-3">

        <label for="quantity" class="pr-4">Quantiter:</label>
        <input class="" type="number" name="quantity" placeholder="0" v-model="quantity">

        <label for="unity">Unité</label>
        <select v-model="unity" name="unity">
          <option value="gr">gr</option>
          <option value="pce">pce</option>
        </select>

      </div>
      <div class="" @click="addIngredientToNewRecipes">Ajouter un autre ingredient</div>
      <div class="ingredientsFields" v-for="(ingredient, index) in newIngredientsRecipe" :key="index">
        <p>{{ ingredient.name }} {{ ingredient.quantity }} {{ ingredient.unity }}</p>
      </div>
      <label class="text-left" for="method">Method de la recette</label>
      <textarea name="method" placeholder="Description de la method de la recette" v-model="method"/>
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
      form: false,
      recipeName: '',
      method: '',
      ingredientSelected: [],
      ingredients: [],
      recipes: [],
      options: [],
      loader: false,
      quantity: '',
      unity: '',
      newIngredientsRecipe: [],
    }
  },
  methods: {
    addIngredientToNewRecipes() {
      this.ingredients.push({
        ingredient : 'api/ingredients/' + this.ingredientSelected.id,
        unity : this.unity,
        quantity: parseInt(this.quantity),
      })
      this.newIngredientsRecipe.push({
        name : this.ingredientSelected.name,
        unity : this.unity,
        quantity: parseInt(this.quantity),
      })
      this.unity = ''
      this.quantity = null
      this.ingredientSelected = []
    },
    newRecipe() {
      this.addIngredientToNewRecipes()
      let params = { name: this.recipeName,
                     method: this.method,
                     recipeIngredients : this.ingredients,
      }
      serverFile.newRecipe(params).then((response) => {
        console.log(response)
      })
    },
    getAllRecipes() {
      serverFile.getAllRecipes().then((response)=>{
        this.recipes = response['hydra:member']
      })
    },
    onSearchIngredient(search, loading) {
      if(search.length) {
        loading(true);
        this.newIngredient = { name: search, label: search }
        this.searchIngredient(loading, search, this);
      }
    },
    searchIngredient: _.debounce((loading, search, vm) => {
      serverFile.findIngredientByName(search).then((response) => {
        if (response) {
          vm.options = response['hydra:member']
          loading(false);
        }
      })
    }, 350),
    addNewIngredient() {
      serverFile.newIngredient(this.newIngredient).then((response) => {
        if (response) {
          this.options.push(response)
        }
      })
    },
  },
  addNotification() {
    this.notify({
      message: 'Welcome',
      type: 'success'
    })
  },
  mounted: function() {
    this.getAllRecipes()
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