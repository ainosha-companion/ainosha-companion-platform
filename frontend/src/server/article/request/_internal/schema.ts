import * as v from "valibot"

export const ArticleRequestSchema = v.object({
    topic: v.pipe(v.string(), v.minLength(1), v.maxLength(255)),
    deep_research: v.optional(v.boolean()),
    style: v.pipe(v.string(), v.minLength(1), v.maxLength(100)),
    target_audience: v.pipe(v.string(), v.minLength(1), v.maxLength(100)),
    category_id: v.pipe(v.number(), v.integer()),
    minimum_words: v.optional(
        v.nullable(
            v.pipe(v.number(), v.integer(), v.minValue(100), v.maxValue(10000)),
        ),
    ),
    maximum_words: v.optional(
        v.nullable(
            v.pipe(v.number(), v.integer(), v.minValue(100), v.maxValue(20000)),
        ),
    ),
    number_of_chapters: v.optional(
        v.nullable(
            v.pipe(v.number(), v.integer(), v.minValue(1), v.maxValue(20)),
        ),
    ),
    introduction_length: v.optional(
        v.nullable(
            v.pipe(v.number(), v.integer(), v.minValue(50), v.maxValue(1000)),
        ),
    ),
    conclusion_length: v.optional(
        v.nullable(
            v.pipe(v.number(), v.integer(), v.minValue(50), v.maxValue(1000)),
        ),
    ),
    reference_articles: v.optional(
        v.nullable(v.array(v.pipe(v.string(), v.url()))),
    ),
    meta_keywords: v.optional(
        v.nullable(v.array(v.pipe(v.string(), v.maxLength(50)))),
    ),
})
