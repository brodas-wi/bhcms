<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|max:255',
            'content' => 'required',
            'type' => 'required|in:article,blog,news',
            'status' => 'required|in:draft,published,scheduled',
            'categories' => 'array',
            'categories.*' => 'exists:categories,id',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
            'published_at' => 'nullable|date',
            'featured_image' => 'nullable|string',
            'excerpt' => 'nullable|string|max:500',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
        ];

        // Si el estado es published y no hay fecha de publicación, usar la fecha actual
        if ($this->input('status') === 'published' && !$this->input('published_at')) {
            $this->merge(['published_at' => now()]);
        }

        return $rules;
    }

    protected function prepareForValidation()
    {
        // Si featured_image está vacío, asegurarse de que sea null
        if (empty($this->featured_image)) {
            $this->merge(['featured_image' => null]);
        }
    }

    public function messages()
    {
        return [
            'title.required' => 'El título es obligatorio',
            'title.max' => 'El título no puede tener más de 255 caracteres',
            'content.required' => 'El contenido es obligatorio',
            'type.required' => 'El tipo de contenido es obligatorio',
            'type.in' => 'El tipo de contenido seleccionado no es válido',
            'status.required' => 'El estado es obligatorio',
            'status.in' => 'El estado seleccionado no es válido',
            'categories.array' => 'Las categorías deben ser un array',
            'categories.*.exists' => 'Una de las categorías seleccionadas no existe',
            'tags.array' => 'Las etiquetas deben ser un array',
            'tags.*.exists' => 'Una de las etiquetas seleccionadas no existe',
            'published_at.date' => 'La fecha de publicación no es válida'
        ];
    }
}
