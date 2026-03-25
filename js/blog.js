/* ============================================
   TORAT HABAYIT — Blog Store & Rendering
   Uses localStorage for post persistence
   ============================================ */

const BlogStore = {
  STORAGE_KEY: 'torathabayit_posts',

  getPosts() {
    try {
      const raw = localStorage.getItem(this.STORAGE_KEY);
      return raw ? JSON.parse(raw) : this._getDefaults();
    } catch(e) {
      return this._getDefaults();
    }
  },

  savePosts(posts) {
    localStorage.setItem(this.STORAGE_KEY, JSON.stringify(posts));
  },

  createPost(post) {
    const posts = this.getPosts();
    const newPost = {
      id: Date.now().toString(),
      title: post.title,
      content: post.content,
      excerpt: post.excerpt || this._extractExcerpt(post.content),
      category: post.category || 'General',
      author: post.author || 'Torat Habayit Institute',
      date: post.date || new Date().toISOString().split('T')[0],
      image: post.image || '',
      status: post.status || 'draft',
      slug: this._slugify(post.title),
    };
    posts.unshift(newPost);
    this.savePosts(posts);
    return newPost;
  },

  updatePost(id, updates) {
    const posts = this.getPosts();
    const idx = posts.findIndex(p => p.id === id);
    if (idx !== -1) {
      posts[idx] = { ...posts[idx], ...updates, updatedAt: new Date().toISOString() };
      this.savePosts(posts);
      return posts[idx];
    }
    return null;
  },

  deletePost(id) {
    const posts = this.getPosts().filter(p => p.id !== id);
    this.savePosts(posts);
  },

  getPost(id) {
    return this.getPosts().find(p => p.id === id) || null;
  },

  getPostBySlug(slug) {
    return this.getPosts().find(p => p.slug === slug) || null;
  },

  _slugify(str) {
    return str.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
  },

  _extractExcerpt(content) {
    const stripped = content.replace(/<[^>]+>/g, '').replace(/\s+/g, ' ').trim();
    return stripped.slice(0, 160) + (stripped.length > 160 ? '...' : '');
  },

  _getDefaults() {
    return [
      {
        id: '1',
        title: 'Brain Death in Halacha: Understanding the Contemporary Debate',
        slug: 'brain-death-in-halacha-contemporary-debate',
        excerpt: 'The question of brain death and organ donation remains one of the most debated issues in contemporary halachic literature. We examine the key positions and their practical implications.',
        content: '<p>The question of brain death and organ donation remains one of the most debated issues in contemporary halachic literature. Over the past several decades, the advancement of medical technology has forced poskim to grapple with questions that previous generations could never have anticipated.</p><p>The crux of the debate revolves around a single fundamental question: does brain death constitute halachic death (mitat ha-nes)? Traditional halachic sources, drawing on the Gemara in Yoma 85a, define death primarily through the cessation of breathing. The question is whether this applies only to irreversible cessation of respiratory function at the lung level, or whether it encompasses the brain's capacity to control breathing.</p><p>Rabbi Moshe Feinstein and Rabbi Shlomo Zalman Auerbach took differing views on this question, and their responsa have become the foundational texts for subsequent discussion. At the Torat Habayit Institute, we have worked to synthesize these positions into practical halachic guidance for the medical community and for families facing these most difficult decisions.</p>',
        category: 'Halacha',
        author: 'Rabbi Yehuda Herzl Finchas',
        date: '2026-03-10',
        image: 'images/books/BD EN Book.jpg',
        status: 'published',
      },
      {
        id: '2',
        title: 'Medications on Yom Kippur: A Practical Halachic Guide',
        slug: 'medications-yom-kippur-practical-guide',
        excerpt: 'As Yom Kippur approaches, many patients with chronic conditions ask whether they may take their medications on the fast. Here is a clear framework for common situations.',
        content: '<p>Each year as Yom Kippur approaches, the phones at the Torat Habayit Institute ring with the same urgent question: "May I take my medication on the fast?" The answer, like so much in halacha, depends on the details of the case.</p><p>The Torah prohibition of eating and drinking on Yom Kippur applies to quantities of food (shiur achila) and liquid. However, the halachic analysis of medications involves several additional layers: Is the substance food-like? Is it bitter? Is it swallowed in pill form? Is the condition life-threatening or potentially dangerous?</p><p>The general framework we use at the Institute is as follows: Medications that are essential for life or that, if missed, would pose a genuine health risk should be taken even on Yom Kippur, as pikuach nefesh overrides all other considerations. Where possible, liquid medications should be made bitter before taking them. Pills may be swallowed in most cases without halachic concern.</p>',
        category: 'Practical Halacha',
        author: 'Torat Habayit Institute',
        date: '2026-02-20',
        image: 'images/books/Chagim Book.jpg',
        status: 'published',
      },
      {
        id: '3',
        title: 'New Seminar Announcement: Genetics and Jewish Law 2026',
        slug: 'genetics-jewish-law-seminar-2026',
        excerpt: 'We are proud to announce our upcoming international seminar series on Genetics and Jewish Law, coming to London, New York, and Tel Aviv in the spring of 2026.',
        content: '<p>We are proud to announce our upcoming international seminar series on Genetics and Jewish Law, coming to London, New York, and Tel Aviv in the spring of 2026.</p><p>The rapid advancement of genetic science has created a new frontier for halachic inquiry. Carrier screening, preimplantation genetic diagnosis (PGD), whole-genome sequencing, and BRCA gene testing all raise profound questions about the nature of medical knowledge, the obligations of disclosure, and the sanctity of human life.</p><p>This seminar series will bring together leading halachic authorities and medical geneticists to explore these questions in depth. Topics will include: the halachic status of genetic information, obligations of disclosure to family members, the permissibility of genetic selection, and the ethics of predictive testing in minors.</p><p>Registration is now open. Spaces are limited. Contact us to reserve your place.</p>',
        category: 'Announcements',
        author: 'Torat Habayit Institute',
        date: '2026-01-15',
        image: 'images/gallery/Genetics Hendon.jpeg',
        status: 'published',
      },
    ];
  }
};

/* ─── Blog Card Template ─── */
function renderBlogCard(post) {
  const dateFormatted = new Date(post.date).toLocaleDateString('en-GB', { day: 'numeric', month: 'long', year: 'numeric' });
  return `
  <article class="blog-card">
    <div class="blog-card-img">
      ${post.image ? `<img src="${post.image}" alt="${post.title}" loading="lazy" onerror="this.style.display='none'">` : ''}
      <span class="blog-cat">${post.category}</span>
    </div>
    <div class="blog-card-body">
      <div class="blog-card-meta">${dateFormatted} · ${post.author}</div>
      <h3>${post.title}</h3>
      <p>${post.excerpt}</p>
      <a href="post.html?id=${post.id}" class="read-more">Read More →</a>
    </div>
  </article>`;
}
