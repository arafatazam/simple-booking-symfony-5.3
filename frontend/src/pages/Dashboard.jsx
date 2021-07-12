import React, { useState, useRef, useEffect } from "react";
import moment from "moment";
import {
  Calendar,
  Modal,
  Spin,
  Typography,
  Form,
  Button,
  InputNumber,
  notification,
  Input,
} from "antd";

const { Text } = Typography;

const api_url = import.meta.env.VITE_API_URL;

export default function Dashboard() {
  const [calendarBusy, setCalenderBusy] = useState(false);
  const [calendarData, setCalendarData] = useState({});
  const [form] = Form.useForm();
  const selecTed = useRef(null);

  const [modalVisible, setModalVisible] = useState(false);
  const [modalBusy, setModalBusy] = useState(false);
  const [modalSubmit, setModalSubmit] = useState("Submit");

  const updateCalender = async (date) => {
    setCalenderBusy(true);
    const startMoment = moment(date.format("YYYY-MM-DD")).date(1).day(0);
    const startDate = startMoment.format("YYYY-MM-DD");
    const endDate = startMoment.add(41, "days").format("YYYY-MM-DD");

    const response = await fetch(`${api_url}/vacancy/${startDate}/${endDate}`);
    const payload = await response.json();
    const vacancyData = payload.reduce((acc, curr) => {
      acc[curr.date] = curr;
      return acc;
    }, {});
    setCalendarData(vacancyData);
    setCalenderBusy(false);
  };

  const onPanelChange = async (date, mode) => {
    if (mode == "year") {
      return;
    }
    await updateCalender(date);
  };

  const renderDateCell = (date) => {
    let key = date.format("YYYY-MM-DD");
    if (!calendarData[key]) {
      return <Text type="secondary">Click To Create Vacancy</Text>;
    }
    return (
      <>
        <p>
          <Text>Availabe - {calendarData[key]["available_slots"]}</Text>
        </p>
        <p>
          <Text type="warning" mark>
            Booked - {calendarData[key]["booked_slots"]}
          </Text>
        </p>
      </>
    );
  };

  const populateModal = async (dateMoment) => {
    const date = dateMoment.format("YYYY-MM-DD");
    form.setFieldsValue({
      date: date,
      available_slots: 1,
      price: 0.0,
    });
    const resp = await fetch(`${api_url}/vacancy/${date}`);
    if (resp.status == 404) {
      setModalSubmit("Create");
      return;
    }
    const vacancy = await resp.json();
    form.setFieldsValue({
      date: vacancy["date"],
      available_slots: vacancy["available_slots"],
      price: vacancy["price"],
    });
    setModalSubmit("Update");
  };

  const onFormSubmit = async (values) => {
    setModalBusy(true);
    let url = api_url + "/vacancy/";
    if (modalSubmit == "Update") {
      url += values["date"];
    }
    try {
      const resp = await fetch(url, {
        method: "POST",
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
        },
        body: JSON.stringify(values),
      });
      notification.open({
        type: "success",
        message: "Success",
        description: `Successfully ${modalSubmit}ed`,
      });
      updateCalender(moment(values["date"]));
    } catch (error) {
      notification.open({
        type: "error",
        message: "Error",
        description: "Error occured",
      });
    }
    setModalVisible(false);
  };

  const onDateChange = (date) => {
    let prev = selecTed.current;
    selecTed.current = date;
    if (
      prev != null &&
      (prev.year() != date.year() || prev.month() != date.month())
    ) {
      return;
    }
    setModalVisible(true);
    setModalBusy(true);
    populateModal(date);
    setModalBusy(false);
  };

  useEffect(() => {
    const date = moment();
    selecTed.current = date;
    updateCalender(date);
  }, []);

  return (
    <>
      <Spin spinning={calendarBusy} size="large">
        <Calendar
          onPanelChange={onPanelChange}
          onSelect={onDateChange}
          dateCellRender={renderDateCell}
        ></Calendar>
      </Spin>
      <Modal
        visible={modalVisible}
        footer={null}
        onCancel={() => setModalVisible(false)}
      >
        <Spin spinning={modalBusy}>
          <Form
            form={form}
            style={{ marginTop: "30px" }}
            onFinishFailed={(errorInfo) =>
              notification.open({ type: "error", message: errorInfo })
            }
            onFinish={onFormSubmit}
          >
            <Form.Item name="date" hidden>
              <Input type="date" />
            </Form.Item>
            <Form.Item
              name="available_slots"
              rules={[{ required: true, type: "integer", min: 1 }]}
              label="Available Slots"
            >
              <InputNumber min={1} placeholder="Input number of slots" />
            </Form.Item>
            <Form.Item
              name="price"
              rules={[{ required: true, type: "number", min: 0 }]}
              label="Price"
            >
              <InputNumber placeholder="Input price" />
            </Form.Item>
            <Form.Item>
              <Button type="primary" htmlType="submit">
                {modalSubmit}
              </Button>
            </Form.Item>
          </Form>
        </Spin>
      </Modal>
    </>
  );
}
