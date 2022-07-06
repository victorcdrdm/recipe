<template>
  <div class="home">
    <h1>Carnet de recette</h1>
    <div class="newRecipes" @click="form = !form">+</div>

    <form v-show="form">
      <input type="text" placeholder="Nom de la recette" v-model="recipeName">
      <v-select @search="onSearchIngredient" :options="options" v-model="ingredientSelected">
        <template slot="no-options" v-if="options">
          Cette ingredient n'existe pas encore.
          <br>
          <button @click="addNewIngredient()">
            Ajouter cette ingredient ?
          </button>
        </template>
        <template slot="option" slot-scope="option" >
          <div class="d-center">
            {{ option.name }}
          </div>
        </template>
        <template slot="selected-option" slot-scope="option">
          <div class="selected d-center" >
            {{ option.name }}
          </div>
        </template>
        <div class="spinner" v-show="loader">Loading...</div>
      </v-select>
      <input type="number" placeholder="Quantiter" v-model="quantity">
      <select v-model="unity">
        <option value="gr">gr</option>
        <option value="pce">pce</option>
      </select>

      <button class="btn btn-classic" @click="addIngredientToNewRecipes">ajouter un autre ingredient</button>
      <div v-if="newIngredientsRecipe">
        <div class="ingredientsFields" v-for="(ingredient, index) in newIngredientsRecipe" :key="index">
          <p> {{ ingredient.name }} {{ingredient.quantity}} {{ingredient.unity}}</p>
        </div>
      </div>
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