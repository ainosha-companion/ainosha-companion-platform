export type Tag = {
    id: number
    name: string
    created_at: string
    updated_at: string
}

export type GetTagsResponse = {
    _metadata: {
        success: boolean
    }
    result: {
        tags: Tag[]
    }
}
