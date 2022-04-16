<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *      normalizationContext={"groups"={"recipies:read"}, "enable_max_depth"=true},
 *      itemOperations={
 *          "put",
 *          "delete",
 *          "get"={"normalizationContext"={"groups"={"recipies:read"}, "enable_max_depth"=true}}
 *      },
 *      collectionOperations={
 *          "post",
 *          "get"={"normalizationContext"={"groups"={"recipies:read"}, "enable_max_depth"=true}}
 *      }
 * )
 * @ORM\Entity(repositoryClass=RecipeRepository::class)
 * @UniqueEntity("name")
 */
class Recipe
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"recipies:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="text", unique=true)
     * @Groups({"recipies:read"})
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=RecipeIngredient::class, mappedBy="recipe")
     * @Groups({"recipies:read"})
     */
    private $recipeIngredients;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $method;

    public function __construct()
    {
        $this->recipeIngredients = new ArrayCollection();
        
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {  
        $this->name = $name;
        //var_dump('youpis');die();
        return $this;
    }

    /**
     * @return Collection<int, RecipeIngredient>
     */
    public function getRecipeIngredients(): Collection
    {
        return $this->recipeIngredients;
    }

    public function addRecipeIngredient(RecipeIngredient $recipeIngredient): self
    {
        if (!$this->recipeIngredients->contains($recipeIngredient)) {
            $this->recipeIngredients[] = $recipeIngredient;
            $recipeIngredient->setRecipe($this);
        }

        return $this;
    }

    public function removeRecipeIngredient(RecipeIngredient $recipeIngredient): self
    {
        if ($this->recipeIngredients->removeElement($recipeIngredient)) {
            // set the owning side to null (unless already changed)
            if ($recipeIngredient->getRecipe() === $this) {
                $recipeIngredient->setRecipe(null);
            }
        }

        return $this;
    }

    public function getMethod(): ?string
    {
        return $this->method;
    }

    public function setMethod(?string $method): self
    {
        $this->method = $method;

        return $this;
    }
}
