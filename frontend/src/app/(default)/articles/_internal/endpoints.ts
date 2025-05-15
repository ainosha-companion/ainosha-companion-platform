export class API_ENDPOINT {
    static GetCategories = "/api/public/categories"
    static GetTags = "/api/public/tags"
    static GetAuthors = "/api/public/authors"
    static GetArticles = "/api/public/articles"
    static AdminGetArticles = "/api/articles"
    static ArticleRequest = "/api/articles"
    static GetOwnerArticleBySlug = (slug: string) => `/api/articles/${slug}`
    static DeleteArticle = (slug: string) => `/api/articles/${slug}`
}
