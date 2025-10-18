type Filter<T extends string> = {
    [K in T]?: string
}
