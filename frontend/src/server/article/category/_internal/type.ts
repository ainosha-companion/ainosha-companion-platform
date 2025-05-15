export type Category = {
    id: number
    name: string
    slug: string
    created_at: string
    updated_at: string
}

export type GetCategoriesResponse = {
    _metadata: {
        success: boolean
    }
    result: {
        categories: Category[]
    }
}
