// JavaScript для переключения сайдбара
document.addEventListener("DOMContentLoaded", function () {
  var sidebar = document.getElementById("sidebar");
  var sidebarToggle = document.getElementById("sidebarToggle");

  sidebarToggle.addEventListener("click", function () {
    var sidebarWidth = sidebar.offsetWidth;

    if (sidebar.style.left === "0px") {
      sidebar.style.left = `-${sidebarWidth}px`;
    } else {
      sidebar.style.left = "0px";
    }
  });
});

// JavaScript для переключения вкладок
document.addEventListener("DOMContentLoaded", () => {
  const tabs = document.querySelectorAll(".tab-link");
  const contents = document.querySelectorAll(".tab-content");

  tabs.forEach((tab) => {
    tab.addEventListener("click", () => {
      const target = document.querySelector(`#${tab.getAttribute("data-tab")}`);

      // Убрать текущий класс со всех вкладок и контентов
      tabs.forEach((t) => t.classList.remove("current"));
      contents.forEach((c) => c.classList.remove("current"));

      // Добавить текущий класс выбранной вкладке и контенту
      tab.classList.add("current");
      target.classList.add("current");
    });
  });
});

window.addEventListener("load", () => {
  // Удаление класса 'current' у всех вкладок
  document.querySelectorAll(".tab-link").forEach((tab) => {
    tab.classList.remove("current");
  });
  // Скрытие всех блоков контента
  document.querySelectorAll(".tab-content").forEach((content) => {
    content.classList.remove("current");
  });
});

// Обработчик кликов по вкладкам, обновляющий их содержимое через AJAX и активирующий поле поиска при наличии
document.querySelectorAll(".tab-link").forEach((tab) => {
  tab.addEventListener("click", function () {
    // Убрать класс 'current' у всех вкладок и контента
    document
      .querySelectorAll(".tab-link")
      .forEach((t) => t.classList.remove("current"));
    document
      .querySelectorAll(".tab-content")
      .forEach((c) => c.classList.remove("current"));

    // Добавить класс 'current' для активной вкладки и контента
    this.classList.add("current");
    const tabId = this.getAttribute("data-tab");
    document.getElementById(tabId).classList.add("current");
    currentPage=1;
    // AJAX запрос для получения данных с учетом текущей страницы
    fetch(`fetch_data.php?tab=${tabId}&page=${currentPage}`)
      .then((response) => response.text())
      .then((html) => {
        document.getElementById(tabId).innerHTML = html;
        currentTabId = tabId;
        // Добавление обработчиков событий на элементы пагинации
        addPaginationEventListeners(tabId);

        // Подгрузка последней страницы при добавлении записи
        const paginationLinks = document.querySelectorAll(`#${tabId} .pagination button`);
        lastPage = paginationLinks.length;
        console.log( paginationLinks.length);
      })
      .catch((error) => console.log("Error loading data:", error));

    // Сброс и скрытие модальной формы при смене вкладки
    closeModal();
  });

  // Активация поля поиска
  const searchInput = document.getElementById("search-input");
  if (searchInput) {
    searchInput.removeAttribute("disabled");
  }
});

// Функция для добавления обработчиков событий на элементы пагинации
function addPaginationEventListeners(tabId) {
  const paginationLinks = document.querySelectorAll(`#${tabId} .pagination a`);
  paginationLinks.forEach((link) => {
    link.addEventListener("click", function (e) {
      e.preventDefault();
      const page = this.getAttribute("data-page");
      loadPage(tabId, page);
    });
  });
}

// обработчик submit события у редактирования
function addSubmitHandlerToEditForm() {
  const form = document.getElementById("editForm");
  if (form) {
    form.addEventListener("submit", function (e) {
      e.preventDefault();
      const formData = new FormData(this);

      fetch(`edit_data.php?tab=${currentTabId}`, {
        method: "POST",
        body: formData,
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.status === "success") {
            updateCurrentTab(currentTabId);
            closeModal();
          } else {
            alert(data.message);
          }
        })
        .catch((error) => {
          console.error("Error:", error);
        });
    });
  } else {
    console.error("Форма редактирования не найдена");
  }
}

// закрытие модальной формы
function closeModal() {
  const modalWrapper = document.getElementById("modalWrapper");
  if (modalWrapper) {
    modalWrapper.classList.remove("show");
    setTimeout(() => {
      modalWrapper.style.display = "none";
    }, 300); // Задержка для завершения анимации прежде чем скрыть модальное окно
  }
}-

// динамическое обновление данных
function updateCurrentTab(tabId) {
  // Обновление текущего идентификатора вкладки
  currentTabId = tabId;

  // Убрать класс 'current' у всех вкладок и контента
  document
    .querySelectorAll(".tab-link")
    .forEach((t) => t.classList.remove("current"));
  document
    .querySelectorAll(".tab-content")
    .forEach((c) => c.classList.remove("current"));

  // Добавить класс 'current' для активной вкладки и контента
  document.querySelector(`[data-tab="${tabId}"]`).classList.add("current");
  document.getElementById(tabId).classList.add("current");

  // AJAX запрос для получения данных
  fetch(
    `fetch_data.php?tab=${tabId}&page=${currentPage}&sortField=${currentSortField}&sortOrder=${currentSortOrder}`
  )
    .then((response) => response.text())
    .then((html) => {
      document.getElementById(tabId).innerHTML = html;
    })
    .catch((error) => console.error("Error loading data:", error));

  // Сброс и скрытие модальной формы при смене вкладки
  closeModal();
}

function loadLastPage(tabId) {
  console.log(lastPage);
  currentPage=lastPage;
  fetch(
    `fetch_data.php?tab=${tabId}&page=${lastPage}&sortField=${currentSortField}&sortOrder=${currentSortOrder}`
  )
    .then((response) => response.text())
    .then((html) => {
      document.getElementById(tabId).innerHTML = html;
    })
    .catch((error) => console.error("Error loading data:", error));
}

function sortTable(sortField) {
  // Определяем направление сортировки
  var sortOrder =
    currentSortField === sortField && currentSortOrder === "ASC"
      ? "DESC"
      : "ASC";
  // Обновляем глобальные переменные
  currentSortField = sortField;
  currentSortOrder = sortOrder;
  // Загрузка страницы с новыми параметрами сортировки
  updateCurrentTab(currentTabId); // Используйте вашу текущую логику для загрузки данных
}

// Функция для загрузки содержимого страницы
function loadPage(tabId, pageNumber) {
  currentPage = 1;

  fetch(
    `fetch_data.php?tab=${tabId}&page=${pageNumber}&sortField=${currentSortField}&sortOrder=ASC`
  )
    .then((response) => response.text())
    .then((html) => {
      document.getElementById(tabId).innerHTML = html;
      // Обновление обработчиков событий для новых элементов пагинации
      addPaginationEventListeners(tabId);
    })
    .catch((error) => alert("Error loading data:", error));
}

// удаление записи
function deleteRecord(record, tableName) {
  if (
    confirm(
      "Вы уверены, что хотите удалить эту запись?\n ВНИМАНИЕ! ВСЕ СВЯЗАННЫЕ С ЭТОЙ СТРОКОЙ ЗАПИСИ БУДУТ УДАЛЕНЫ ТОЖЕ!"
    )
  ) {
    fetch(`delete_record.php?tab=${currentTabId}`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ record: record, table: tableName }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.status === "success") {
          alert("Запись успешно удалена");
          updateCurrentTab(currentTabId); // Обновление данных текущей вкладки
        } else {
          alert("Ошибка при удалении: " + data.message);
        }
      })
      .catch((error) => {
        console.error("Error:", error);
      });
  }
}

// создание модальной формы редактирования
function createEditModal(tableName, recordId) {
  const tableInfo = tableStructures[tableName];
  if (!tableInfo) {
    console.error("Не найдена структура таблицы:", tableName);
    return null;
  }

  // Создание нового модального окна
  let modalWrapper = document.getElementById("modalWrapper");
  if (modalWrapper) {
    modalWrapper.remove();
  }
  modalWrapper = document.createElement("div");
  modalWrapper.id = "modalWrapper";
  document.body.appendChild(modalWrapper);

  let formContainer = document.createElement("div");
  formContainer.id = "editModal";

  // Создание формы
  const form = document.createElement("form");
  form.id = "editForm";
  // Динамическое создание полей для редактирования
  tableInfo.fields.forEach((field) => {
    const label = document.createElement("label");
    label.htmlFor = `edit${field}`;
    label.textContent = `${russianColumnNames[field]}:`;

    const input = document.createElement("input");
    input.type = "text";
    input.id = `edit${field}`;
    input.name = field;

    form.appendChild(label);
    form.appendChild(input);
    form.appendChild(document.createElement("br"));
  });

  // Создание скрытого поля для первичного ключа
  const hiddenIdInput = document.createElement("input");
  hiddenIdInput.type = "hidden";
  hiddenIdInput.id = "editId";
  hiddenIdInput.name = "id"; // Используем имя первичного ключа из структуры таблицы
  hiddenIdInput.value = recordId;
  form.appendChild(hiddenIdInput);

  // Добавление кнопки отправки
  const submitButton = document.createElement("button");
  submitButton.setAttribute("class", "button-glass");
  submitButton.type = "submit";
  submitButton.textContent = "Сохранить изменения";
  form.appendChild(submitButton);

  // Добавление кнопки отмены
  const cancelButton = document.createElement("button");
  cancelButton.setAttribute("class", "close-button");
  cancelButton.type = "button"; // Установите тип button, чтобы предотвратить отправку формы
  cancelButton.textContent = "Отмена";
  cancelButton.onclick = function () {
    closeModal(); // Закрытие модального окна
  };
  form.appendChild(cancelButton);

  formContainer.appendChild(form);
  modalWrapper.appendChild(formContainer);

  return form; // Возвращаем созданную форму
}

// редактирование записи
function editRecord(record, tableName) {
  const tableInfo = tableStructures[tableName];
  if (!tableInfo) {
    console.error("Не найдена структура таблицы:", tableName);
    return;
  }

  const form = createEditModal(tableName, record[tableInfo.primaryKey]);
  if (!form) {
    console.error("Не удалось создать форму редактирования");
    return;
  }

  // Заполнение формы данными
  Object.keys(record).forEach((key) => {
    const input = form.querySelector(`#edit${key}`);
    if (input) {
      input.value = record[key];
    }
  });

  // Удаление предыдущих кнопок, если они существуют
  removePreviousButtons(form);

  // Добавление кнопки отправки
  const submitButton = createButton(
    "button-glass",
    "submit",
    "Сохранить изменения"
  );
  form.appendChild(submitButton);

  // Добавление кнопки отмены
  const cancelButton = createButton("close-button", "button", "", closeModal);
  form.appendChild(cancelButton);

  // Показ модального окна
  showModal(modalWrapper);
  addSubmitHandlerToEditForm();
}

// удаление кнопок с формы
function removePreviousButtons(form) {
  const existingButtons = form.querySelectorAll("button");
  existingButtons.forEach((button) => button.remove());
}

// создание кнопок на форме
function createButton(className, type, text, onClick) {
  const button = document.createElement("button");
  button.setAttribute("class", className);
  button.type = type;
  button.textContent = text;
  if (onClick) {
    button.onclick = onClick;
  }
  return button;
}

// показ формы редактирования
function showModal() {
  const modal = document.getElementById("modalWrapper");
  if (modal) {
    modal.style.display = "flex";
    // modal.className='editModal';
    console.log(modal);
    setTimeout(() => {
      modal.classList.add("show");
    }, 10);
  }
}

// создание модальной формы добавления данных
function createAddModal(tableName) {
  const tableInfo = tableStructures[tableName];
  if (!tableInfo) {
    console.error("Не найдена структура таблицы:", tableName);
    return null;
  }

  // Создание нового модального окна
  let modalWrapper = document.getElementById("modalWrapper");
  if (modalWrapper) {
    modalWrapper.remove();
  }
  modalWrapper = document.createElement("div");
  modalWrapper.id = "modalWrapper";
  modalWrapper.class = "modalWrapper";
  document.body.appendChild(modalWrapper);
  const formContainer = document.createElement("div");
  formContainer.id = "editModal";
  // Создание формы
  const form = document.createElement("form");
  form.id = "addForm";
  // Динамическое создание полей для ввода данных
  tableInfo.fields.forEach((field) => {
    if (field !== tableInfo.primaryKey) {
      // Исключение первичного ключа
      const label = document.createElement("label");
      label.htmlFor = `add${field}`;
      label.textContent = `${russianColumnNames[field]}:`;

      const input = document.createElement("input");
      input.type = "text";
      input.id = `add${field}`;
      input.name = field;

      form.appendChild(label);
      form.appendChild(input);
      form.appendChild(document.createElement("br"));
    }
  });

  // Добавление кнопки отправки
  const submitButton = document.createElement("button");
  submitButton.setAttribute("class", "button-glass");
  submitButton.type = "submit";
  submitButton.textContent = "Добавить запись";
  form.appendChild(submitButton);

  // Добавление кнопки отмены
  const cancelButton = document.createElement("button");
  cancelButton.setAttribute("class", "close-button");
  cancelButton.type = "button";
  cancelButton.onclick = function () {
    closeModal();
  };
  form.appendChild(cancelButton);

  formContainer.appendChild(form);
  modalWrapper.appendChild(formContainer);

  return form; // Возвращаем созданную форму
}

// добавление записи
function addRecord(tableName) {
  const form = createAddModal(tableName);
  if (!form) {
    console.error("Не удалось создать форму добавления");
    return;
  }

  // Показ модального окна
  showModal();

  // Добавление обработчика события 'submit' для формы
  form.addEventListener("submit", function (e) {
    e.preventDefault();
    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());

    fetch(`add_record.php?tab=${currentTabId}`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json", // Указываем, что отправляем данные в формате JSON
      },
      body: JSON.stringify(data), // Преобразуем данные формы в JSON
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.status === "success") {
          closeModal();
          loadLastPage(currentTabId);
        } else {
          alert("Ошибка при удалении: " + data.message);
        }
      })
      .catch((error) => {
        alert("Ошибка:", data.message);
      });
  });
}

function executeSearch() {
  var searchQuery = document.getElementById("search-input").value.toString();

  if (searchQuery) {
    try {
      window.location.href = `search.php?query=${searchQuery}&tab=${currentTabId}`;
    } catch (error) {
      alert(error);
    }
  } else {
    alert("Введите запрос для поиска");
  }
}
