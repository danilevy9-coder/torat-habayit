/* ============================================
   TORAT HABAYIT — Blog Store & Rendering
   Uses localStorage for post persistence
   ============================================ */

const BlogStore = {
  getApiUrl() {
    return window.location.pathname.includes('/admin/') ? '../api/posts.php' : 'api/posts.php';
  },

  async getPosts() {
    try {
      const res = await fetch(this.getApiUrl());
      if (!res.ok) throw new Error('Network response was not ok');
      return await res.json();
    } catch(e) {
      console.error("Failed to load posts:", e);
      return [];
    }
  },

  async createPost(post) {
    const res = await fetch(this.getApiUrl(), {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(post)
    });
    return await res.json();
  },

  async updatePost(id, updates) {
    updates.id = id;
    const res = await fetch(this.getApiUrl(), {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(updates)
    });
    return await res.json();
  },

  async deletePost(id) {
    const res = await fetch(`${this.getApiUrl()}?id=${id}`, {
      method: 'DELETE'
    });
    return await res.json();
  },

  async getPost(id) {
    const posts = await this.getPosts();
    return posts.find(p => p.id === id) || null;
  },

  async getPostBySlug(slug) {
    const posts = await this.getPosts();
    return posts.find(p => p.slug === slug) || null;
  }
};

/* ─── Blog Card Template ─── */
function renderBlogCard(post) {
  const dateFormatted = new Date(post.date).toLocaleDateString('en-GB', { day: 'numeric', month: 'long', year: 'numeric' });
  const catKey = post.category === 'Halacha' ? 'blog_cat_halacha' : (post.category === 'Practical Halacha' ? 'blog_cat_practical' : 'blog_cat_announcements');
  const authorKey = post.author.includes('Rabbi') ? 'blog_auth_1' : 'blog_auth_2';
  const titleKey = `blog_post_${post.id}_tit`;
  const excKey = `blog_post_${post.id}_exc`;

  return `
  <article class="blog-card">
    <div class="blog-card-img">
      ${post.image ? `<img src="${post.image}" alt="${post.title}" loading="lazy" onerror="this.style.display='none'">` : ''}
      <span class="blog-cat" data-i18n="${catKey}">${post.category}</span>
    </div>
    <div class="blog-card-body">
      <div class="blog-card-meta">${dateFormatted} · <span data-i18n="${authorKey}">${post.author}</span></div>
      <h3 data-i18n="${titleKey}">${post.title}</h3>
      <p data-i18n="${excKey}">${post.excerpt}</p>
      <a href="post.html?id=${post.id}" class="read-more" data-i18n="blog_read_more">Read More →</a>
    </div>
  </article>`;
}
