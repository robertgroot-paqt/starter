function replaceParameters(url: string, urlParameters: Record<string, unknown>) {
    Object.entries(urlParameters).forEach(([key, value]) => {
        url = url.replace(`{${key}}`, String(value));
    });

    return url;

}

export function useApi<
    TResponse extends Record<string, unknown>
>({
    url,
    method,
    urlParameters,
    data,
    query,
}: {
    url: string;
    method: string;
    data?: Record<string, unknown>;
    urlParameters?: Record<string, unknown>;
    query?: Record<string, unknown>;
}) {
    url = url.replace('api/v1', '');

    if (urlParameters) {
        url = replaceParameters(url, urlParameters)
    }

    const client = useSanctumClient();

    return client<TResponse>(url, {
        method: method,
        body: data,
        query: query,
    });
};
