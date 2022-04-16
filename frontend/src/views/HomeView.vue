<template>
  <div class="home">
    <h1>Carnet de recette</h1>
    <div class="newRecipes" @click="showForm = !showForm">+</div>
    <form v-show="showForm">
      <input type="text" placeholder="Nom de la recette" v-model="name">
      <div class="ingredientsFields" v-for="ingredient, index in newIngredients" :key="index">
        <input type="text" placeholder="Ingredient" v-model="ingredient.name">
        <input type="number" placeholder="Quantiter" v-model="ingredient.quantity">
        <select v-model="ingredient.unity">
          <option value="gr">gr</option>
          <option value="pce">pce</option>
        </select> 
      </div>
      <div class="newRecipes" @click="addFields">+</div>
      <textarea name="method" v-model="method"/>
    </form>

     <button @click="newRecipe">Soumettre le formulaire</button>
    <div class="ingredients" v-for="ingredient, index in ingredients" :key="index">
      <p> {{ingredient.id}}  {{ingredient.name}} </p>
    </div>
    <hr>
    <div class="recipies" v-for="recipe, i in recipies" :key="i">
      <p> {{recipe.name}} </p>
      <div class="ingredients" v-for="ingredient, j in recipe.recipeIngredients" :key="j">
       <p> {{ingredient.ingredient.name}} {{ingredient.quantity}} {{ingredient.unity}} </p> 
      </div>
      <hr>
    </div>
     
  </div>
</template>

<script>

import serverFile from '../api/ServerFile'

export default {
  name: 'HomeView',
  components: {
  },
  data() {
    return {
      showForm: false,
      name: '',
      method: '',
      ingredients: [],
      recipies: [],
      newIngredients: [{
        name: '',
        quantity: Number,
        unity: '',
      }],
    }
  },
  methods: {
    addFields() {
      this.newIngredients.push({
        name: '',
        quantity: Number,
        unity: ''
      })
    },
    newRecipe() {
      let recipes = {1: 'crumble', 2:"test2", 3:'Super'}
      console.log()
      let params = { name: this.name,
                     method: this.method }
       console.log(this.name in recipes)              
      serverFile.newRecipe(params).then((response) => {
       console.log(response)
      })
    },
    getAllRecipies() {
      serverFile.getAllRecipes().then((response)=>{
        this.recipies = response['hydra:member']
      })
    },
    getAllIngredients() {
      serverFile.getAllIngredients().then((response) => {
        this.ingredients = response['hydra:member']
      })
    }
  },
  mounted: function() {
    this.getAllRecipies()
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