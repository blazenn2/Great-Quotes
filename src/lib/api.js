const fetchDomain = 'http://localhost/React/React-Quotes/React-router-project/quotes.php';
// const fetchDomain = "C:/xampp/htdocs/React/React-Quotes/React-router-project/quotes.php"

export async function getAllQuotes() {
  const response = await fetch(`${fetchDomain}`, {
    method: 'POST',
    headers: {
      "Content-Type": "application/json-getAllQuotes"
    }
  });
  const data = await response.json();

  if (!response.ok) {
    throw new Error(data.message || 'Could not fetch quotes.');
  }

  const transformedQuotes = [];

  for (const key in data) {
    const quoteObj = {
      id: key,
      ...data[key],
    };

    transformedQuotes.push(quoteObj);
  }

  return transformedQuotes;
}

export async function getSingleQuote(quoteId) {
  const response = await fetch(`${fetchDomain}`, {
    method: "POST",
    body: JSON.stringify(quoteId),
    headers: {
      "Content-Type": "application/json-single-quote"
    }
  });
  const data = await response.json();

  if (!response.ok) {
    throw new Error(data.message || 'Could not fetch quote.');
  }

  const loadedQuote = {
    id: quoteId,
    ...data,
  };

  return loadedQuote;
}

export async function addQuote(quoteData) {
  const response = await fetch(`${fetchDomain}`, {
    method: 'POST',
    body: JSON.stringify(quoteData),
    headers: {
      'Content-Type': 'application/json-add-quote',
    },
  });
  const data = await response.text();

  if (!response.ok) {
    throw new Error(data.message || 'Could not create quote.');
  }

  return null;
}

export async function addComment(requestData) {
  const object = {
    text: requestData.commentData.text,
    quoteId: requestData.quoteId
  }
  // console.log(object)
  const response = await fetch(`${fetchDomain}`, {
    method: 'POST',
    body: JSON.stringify(object),
    headers: {
      'Content-Type': 'application/json-add-comment',
    },
  });
  const data = await response.text();
  console.log(data);

  if (!response.ok) {
    throw new Error(data.message || 'Could not add comment.');
  }

  return { commentId: data.name };
}

export async function getAllComments(quoteId) {
  const response = await fetch(`${fetchDomain}`, {
    method: "POST",
    body: JSON.stringify(quoteId),
    headers: {
      "Content-Type": "application/json-show-comments",
    }
  });

  const data = await response.json();

  if (!response.ok) {
    throw new Error(data.message || 'Could not get comments.');
  }

  const transformedComments = [];

  for (const key in data) {
    const commentObj = {
      id: key,
      ...data[key],
    };

    transformedComments.push(commentObj);
  }

  return transformedComments;
}
